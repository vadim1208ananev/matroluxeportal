<?php

namespace api\controllers;

use common\models\Attr;
use common\models\Post;
use common\models\Product;
use common\models\ProductAttr;
use common\models\ProductDiscount;
use common\models\ProductSize;
use common\rbac\Rbac;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\ActiveController;

class FilterController extends ActiveController
{
    public $modelClass = 'common\models\Attr';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

//        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        $data = $productItems = [];
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        $get = Yii::$app->request->get();

        if (!isset($get['category_id'])) return;
        $categoryId = $get['category_id'];

        $query = Product::getProductsQuery([], $categoryId);
        $products = $query->all();
        $productIds = Product::getProductIds($products);

        if (isset($get['f'])) {
            $filter = Attr::parseFilterUrl($get['f']);
            $attrProducts = ProductAttr::getAttrProducts($filter, $productIds);
            $productIds = ProductAttr::getProductIdsFilter($filter, $attrProducts);
            $query = Product::getProductsQuery($productIds, $categoryId);
        }

        $attrIds = ProductAttr::getAttrIds($productIds);
        $attrGroupIds = Attr::getAttrGroupIds($attrIds);
        $attrs = Attr::getAttrs($attrGroupIds, $attrIds);

        foreach ($attrs as $attrGroup) {
            $items = [];
            foreach ($attrGroup['attrs'] as $attr) {
                $items[] = [
                    'attr_id' => $attr['attr_id'],
                    'name' => $attr['name'],
                    'url' => $attr['url_raw']
                ];
            }
            $data['attrGroups'][$attrGroup['attr_group_id']] = [
                'attr_group_id' => $attrGroup['attr_group_id'],
                'name' => $attrGroup['name'],
                'url' => $attrGroup['url'],
                'attrs' => $items
            ];
        }

        foreach ($query->all() as $item) {
            $productItems[] = [
                'name' => $item->productDesc->name,
                'url' => Yii::$app->request->hostInfo . DIRECTORY_SEPARATOR . $item->url . DIRECTORY_SEPARATOR . 'p' . $item->product_id,
                'img' => Yii::$app->request->hostInfo . DIRECTORY_SEPARATOR . $item->getImage()->getPath('700x')
            ];
        }

        $data['products'] = $productItems;
        return $data;
    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }

}