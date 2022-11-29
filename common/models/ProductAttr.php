<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ProductAttr extends ActiveRecord
{

    public $value;

    public function rules()
    {
        return [
            [['value'], 'safe'],
        ];
    }

//    public function getAttr()
//    {
//        return $this->hasOne(Attr::className(), ['attr_id' => 'attr_id'])->
//        OnCondition(['attr_group_id' => Yii::$app->params['main.size_attr_group_id']]);
//    }

    public function getAttr()
    {
        return $this->hasOne(Attr::className(), ['attr_id' => 'attr_id']);
    }

    public function getAttrDesc()
    {
        return $this->hasOne(AttrDesc::className(), ['attr_id' => 'attr_id'])
            ->onCondition(['lang_id' => Yii::$app->language]);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

    public static function getProductAttrs($product_id)
    {
        return ProductAttr::find()
            ->with('attrDesc.attr.attrGroup.attrGroupDesc')
            ->where(['product_id' => $product_id]);
    }

    public static function getAttrIds($productIds)
    {
        $productAttr = ProductAttr::find()
            ->select('attr_id')
            ->where(['in', 'product_id', $productIds])
            ->distinct()
            ->asArray()
            ->all();
        return array_reduce($productAttr, function ($carry, $item) {
            $carry[] = reset($item);
            return $carry;
        }, []);
    }

    public static function getAttrProducts($filter, $productIds)
    {
        $attrs = array_reduce($filter, function ($carry, $item) {
            $carry = array_merge($carry, array_keys($item['attrs']));
            return $carry;
        }, []);

        $attrs = Attr::find()
            ->joinWith([
                'productAttrs' => function ($query) use ($productIds, $attrs) {
                    $query->onCondition(['in', 'product_attr.product_id', $productIds]);
                }
            ])
            ->where(['in', 'attr.attr_id', $attrs])
            ->asArray()
            ->all();

        return array_reduce($attrs, function ($carry, $item) {
            $carry[$item['attr_id']] = array_column($item['productAttrs'], 'product_id');
            return $carry;
        }, []);
    }

    public static function getProductIdsFilter($filter, $attrProducts)
    {
        $result = [];
        $first = true;
        foreach ($filter as $agId => $ag) {
            $carry = [];
            foreach ($ag['attrs'] as $aId => $a) {
                $carry = array_unique(array_merge($carry, $attrProducts[$aId]));
            }
            if ($first) {
                $result = $carry;
            } else {
                $result = array_intersect($result, $carry);
            }
            $first = false;
        }
        return empty($result) ? [0] : $result;
    }
}

