<?php

namespace app\modules\backend\controllers;

use common\models\AttrGroup;
use common\models\ProductAttr;
use common\models\ProductSearch;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use common\models\Product;
use yii\web\UploadedFile;
use common\models\Attr;
use common\models\ProductDesc;
use common\models\Size;
use common\models\ProductSize;
use common\services\Utils;

class ProductController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update'],
                        'allow' => true,
                        'roles' => ['admin', 'backend']
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = [];
        $data['products'] = Product::find()
            ->orderBy(['product_id' => SORT_DESC])
            ->all();

        return $this->render('index', $data);
    }

    public function actionUpdate($id)
    {
        $data = [];
        $request = Yii::$app->request;
        $product = Product::findOne($id);
        $productDesc = $product->productDesc;
        $productAttrsPost = [];

        $attrs = AttrGroup::find()
            ->with('attrGroupDesc', 'attrs', 'attrDesc')
            ->where(['!=', 'attr_group_id', AttrGroup::ATTR_GROUP_RAZMER])
            ->asArray()
            ->all();
        $productAttrs = ProductAttr::find()
            ->where(['product_id' => $id])
            ->indexBy('attr_id')
            ->all();
        foreach ($attrs as $ag) {
            foreach ($ag['attrs'] as $a) {
                $attrId = $a['attr_id'];
                if (array_key_exists($attrId, $productAttrs)) {
                    $pa = $productAttrs[$attrId];
                    $pa->value = 1;
                    $productAttrsPost[$attrId] = $pa;
                } else {
                    $pa = new ProductAttr();
                    $pa->product_id = $id;
                    $pa->attr_id = $attrId;
                    $productAttrsPost[$attrId] = $pa;
                }
            }
        }

        $productSearch = ProductSearch::findOne($id);

        if ($request->isPost) {
            $product->load($request->post()) && $product->save(false);
            $productDesc->load($request->post()) && $productDesc->save(false);

            $product->images = UploadedFile::getInstances($product, 'images');
            if ($product->upload()) {
                // file is uploaded successfully
            }

            if (Model::loadMultiple($productAttrsPost, $request->post()) && Model::validateMultiple($productAttrsPost)) {
                foreach ($productAttrsPost as $attrId => $item) {
                    if (array_key_exists($attrId, $productAttrs) && !$item->value) {
                        $item->delete();
                    }
                    if (!array_key_exists($attrId, $productAttrs) && $item->value) {
                        $item->save(false);
                    }
                }
            };

            $productSearch->load($request->post()) && $productSearch->save(false);

            return $this->refresh();
        }

        return $this->render('update', [
            'product' => $product,
            'productDesc' => $productDesc,
            'attrs' => $attrs,
            'productAttrsPost' => $productAttrsPost,
            'productSearch' => $productSearch,
        ]);

    }

    public function actionCreate()
    {
        $data = $productIds = [];
        $product = new Product();
        $productIds = [];
        $categoryId = 1;
        $bonus = 0.5;
        $langId = 'ru';
        $data['product'] = $product;
        $request = Yii::$app->request;

        $sizes = Size::find()
            ->indexBy('name')
            ->asArray()
            ->all();

        $attrs = Attr::find()
            ->indexBy('name')
            ->asArray()
            ->all();

        if ($request->isPost) {
            if ($product->load($request->post())) {
                $rows = explode(PHP_EOL, $product->textArea);
                foreach ($rows as $row) {
                    $tmp = preg_split('/\t/', trim($row));
                    $hasSize = isset($tmp[2]);
                    $productName = $tmp[0];
                    $product1cId = $tmp[1];
                    if ($hasSize) {
                        $sizeName = $tmp[2];
                        $size1cId = $tmp[3];
                    }

                    if (array_key_exists($product1cId, $productIds)) {
                        $product = $productIds[$product1cId];
                        $productId = $product->product_id;
                    } else {
                        $record = Product::find()
                            ->where(['1c_id' => $product1cId]);
                        if ($record->exists()) {
                            $product = $record->one();
                            $productId = $product->product_id;
                            $productIds[$product1cId] = $product;
                        } else {
                            $product = new Product();
                            $product->category_id = $categoryId;
                            $product->name = $productName;
                            $product->url = Utils::translitForUrl($productName);
                            $product['1c_id'] = $product1cId;
                            $product->bonus = $bonus;
                            $product->status = null;
                            $product->save(false);
//                Yii::$app->db->getLastInsertID()
                            $productId = $product->product_id;

                            $productIds[$product1cId] = $product;

                            $productDesc = new ProductDesc();
                            $productDesc->product_id = $productId;
                            $productDesc->lang_id = $langId;
                            $productDesc->name = $productName;
                            $productDesc->save(false);
                        }

                    }

                    if (!ProductSearch::find()
                        ->where(['product_id' => $productId])
                        ->exists()) {
                        $productSearch = new ProductSearch();
                        $productSearch->product_id = $productId;
                        $productSearch->search = $productName;
                        $productSearch->save(false);
                    }

                    if ($hasSize) {
                        $tmp = array_map(function ($value) {
                            return intval(str_replace(mb_chr(160), '', $value)) / 10;
                        }, explode(', ', $sizeName));
                        $size = $tmp[0] . 'x' . $tmp[1];

                        if (array_key_exists($size, $sizes)) {
                            if (!ProductSize::find()
                                ->where(['product_id' => $productId, 'size_id' => $sizes[$size]['size_id']])
                                ->exists()) {
                                $productSize = new ProductSize();
                                $productSize->product_id = $productId;
                                $productSize->size_id = $sizes[$size]['size_id'];
                                $productSize['1c_id'] = $size1cId;
                                $productSize->save(false);
                            }
                        }

                        if (array_key_exists($size, $attrs)) {
                            if (!ProductAttr::find()
                                ->where(['product_id' => $productId, 'attr_id' => $attrs[$size]['attr_id']])
                                ->exists()) {
                                $productAttr = new ProductAttr();
                                $productAttr->product_id = $productId;
                                $productAttr->attr_id = $attrs[$size]['attr_id'];
                                $productAttr->save(false);
                            }
                        }
                    } else {
                        //для номенклатуры без учета по характеристикам добавляем стандартную характеристику, чтобы не менять везде код
                        if (!ProductSize::find()
                            ->where(['product_id' => $productId, 'size_id' => Size::STANDART_SIZE_ID])
                            ->exists()) {
                            $productSize = new ProductSize();
                            $productSize->product_id = $productId;
                            $productSize->size_id = Size::STANDART_SIZE_ID;
                            $productSize->save(false);
                        }
                    }

                }
            }
        }

        return $this->render('create', $data);
    }

}
