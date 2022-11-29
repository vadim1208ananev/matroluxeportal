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
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class ProductController extends ActiveController
{
    public $modelClass = 'common\models\Product';

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
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        $get = Yii::$app->request->get();

        //codes
        if (isset($get['method']) && ($get['method'] == 'codes')) {
            return ArrayHelper::getColumn(Product::find()
                ->select('1c_id')
                ->where(['not', ['1c_id' => null]])
                ->asArray()
                ->all(), '1c_id');
        }

    }

    public function actionUpdate()
    {

        $db = Yii::$app->db;
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        $get = Yii::$app->request->get();
        $pd = $pa = [];

        //discount
        if (isset($get['method']) && ($get['method'] == 'discount')) {
            $transaction = $db->beginTransaction();
            $products1c = $bodyParams['products'];
            $productsDb = Product::find()
                ->where(['not', ['1c_id' => null]])
                ->indexBy('1c_id')
                ->asArray()
                ->all();

            foreach ($products1c as $item) {
                if (array_key_exists($item['product_1c_id'], $productsDb)) {
                    $pd[] = [$productsDb[$item['product_1c_id']]['product_id']];
                    $pa[] = [$productsDb[$item['product_1c_id']]['product_id'], Attr::ATTR_DISCOUNT_ID];
                }
            }

            try {
                ProductDiscount::deleteAll();
                $db->createCommand()->batchInsert('product_discount', ['product_id'], $pd)->execute();
                ProductAttr::deleteAll(['attr_id' => Attr::ATTR_DISCOUNT_ID]);
                $db->createCommand()->batchInsert('product_attr', ['product_id', 'attr_id'], $pa)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (Throwable $e) {
                $transaction->rollBack();
            }
            return [
                'status' => true
            ];
        }

        //retail prices
        if (isset($get['method']) && ($get['method'] == 'prices')) {
            $size1cIds = $bodyParams['size1cIds'];
            $sizes = ProductSize::find()
                ->where(['not', ['1c_id' => null]])
                ->indexBy('1c_id')
                ->all();
            foreach ($size1cIds as $item) {
                if (array_key_exists($item['size_1c_id'], $sizes)
                    && intval($sizes[$item['size_1c_id']]['price']) != $item['price']) {
                    $productSize = $sizes[$item['size_1c_id']];
                    if ($productSize) {
                        $productSize->price = $item['price'];
                        $productSize->save(false);
                    }
                }
            }
        }

    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }

}