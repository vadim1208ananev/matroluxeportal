<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Product extends ActiveRecord
{

    public $images;
    public $textArea;

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    public function rules()
    {
        return [
            [['name', 'sort_order', 'status', 'url', 'bonus', 'textArea'], 'safe'],
        ];
    }

    public function getProductDesc()
    {
        return $this->hasOne(ProductDesc::className(), ['product_id' => 'product_id'])->
        OnCondition(['product_desc.lang_id' => Yii::$app->language]);
    }

    public function getProductAttrs()
    {
        return $this->hasMany(ProductAttr::className(), ['product_id' => 'product_id']);
    }

    public function getProductDiscount()
    {
        return $this->hasOne(ProductDiscount::className(), ['product_id' => 'product_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public static function getProductsQuery($productIds = [], $categoryId = null)
    {
        $query = Product::find()
            ->with(['productDesc'])
            ->where(['status' => 1])
            ->orderBy('product.sort_order ASC');
        if ($categoryId) {
            $query = $query
                ->andWhere(['category_id' => $categoryId]);
        };
        if ($productIds) {
            $query = $query->andWhere(['in', 'product_id', $productIds]);
        }
        return $query;
    }

    public static function getProductsSearchQuery($productIds = [])
    {
        $products = Product::find()
            ->with(['productDesc'])
            ->where(['status' => 1])
            ->orderBy('product.sort_order ASC');
        $products = $products->andWhere(['in', 'product_id', $productIds]);
        return $products;
    }

    public static function getProductIds($products)
    {
        if (!$products) {
            return [];
        }
        $productsIds = array_reduce($products, function ($carry, $item) {
            $carry[] = $item['product_id'];
            return $carry;
        });
        return $productsIds;
    }

    public static function getProductIdsSearch($q, $limit = 0)
    {
        $products = ProductSearch::find()
            ->select('product_id')
            ->distinct()
            ->where(['like', 'search', $q]);
        if ($limit)
            $products = $products->limit($limit);
        $products = $products
            ->asArray()
            ->all();
        return self::getProductIds($products);
    }

//    public function getOrderProduct($productId, $attrId)
//    {
//        return Product::find()->
//        where(['product.product_id' => $productId])->
//        joinWith([
//            'productDesc' => function ($q) use ($attrId) {
//                $q->joinWith([
//                    'productAttrs' => function ($q) use ($attrId) {
//                        $q->onCondition(['product_attr.attr_id' => $attrId])->
//                        joinWith([
//                            'attr' => function ($q) {
//                                $q->joinWith([
//                                    'attrDesc' => function ($q) {
//                                    }
//                                ]);
//                            }
//                        ]);
//                    }
//                ]);
//            }
//        ])->
//        one();
//    }

    public static function getCartSpecProducts($cart)
    {
//        $array = [
//            'product_id' => [
//                'size_id' => 6,
//                'size_id' => 5,
//                'size_id' => 2,
//            ]
//        ];

        $data = [];
        $productIds = array_keys($cart);
        $query = Product::getProductsQuery($productIds);
        $products = $query->all();
        $sizes = ProductSize::getProductSizes($productIds);
        foreach ($products as $p) {
            foreach ($cart[$p->product_id] as $sId => $count) {
                $data[] = [
                    'product' => $p,
                    'sizeId' => $sId,
                    'sizeName' => Size::isStandart($sId) ? $sizes[$p->product_id][$sId]['name'] : $sId,
                    'count' => $count
                ];
            }
        }
        return $data;
    }

    public static function hasNostandart($product)
    {
        //only mattresses and mattresses protection are nostandart order available
        if ($product->category_id == 1)
            return true;
        $accs = ProductAttr::find()
            ->where(['attr_id' => Attr::ATTR_MATTRESS_PROTECTION_ID])
            ->indexBy('product_id')
            ->asArray()
            ->all();
        if (array_key_exists($product->product_id, $accs))
            return true;
        return false;
    }

    //backend

    public function upload()
    {
        if ($this->validate()) {
            if ($this->images)
                $this->removeImages();
            foreach ($this->images as $key => $file) {
                $path = 'images/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $this->attachImage($path, $key == 0 ? true : false);
                @unlink($path);
            }
            return true;
        }
        return false;
    }

}
