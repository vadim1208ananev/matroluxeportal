<?php

namespace api\controllers;

use common\models\Post;
use common\models\Product;
use common\models\ProductSize;
use common\models\Stock;
use common\models\Warehouse;
use common\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class StockController extends ActiveController
{
    public $modelClass = 'common\models\Stock';

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
        $data = [];
        $get = Yii::$app->request->get();

        //codes
        if (isset($get['method']) && ($get['method'] == 'codes')) {
            $warehouses = Warehouse::find()
                ->all();
            foreach ($warehouses as $w) {
                $data['warehouses'][] = $w['1c_id'];
            }
            $products = Product::find()
                ->where(['not', ['1c_id' => null]])
                ->all();
            foreach ($products as $p) {
                $data['products'][] = $p['1c_id'];
            }
            return $data;
        }

        //without method
        $stocks = Warehouse::find()
            ->with([
                'stocks' => function ($query) {
                    $query
                        ->with([
                            'productDesc' => function ($query) {
                            }])
                        ->with([
                            'sizeDesc' => function ($query) {
                            }])
                        ->where(['>', 'stock', 0]);
                }
            ])
            ->asArray()
            ->all();

        $data = array_reduce($stocks, function ($c1, $i1) {
            $c1[] = [
                'warehouse_id' => $i1['warehouse_id'],
                'name' => $i1['name'],
                'stocks' => array_reduce($i1['stocks'], function ($c2, $i2) {
                    $c2[] = [
                        'product_id' => $i2['product_id'],
                        'product_name' => $i2['productDesc']['name'],
                        'size_id' => $i2['size_id'],
                        'size_name' => $i2['sizeDesc']['name'],
                        'stock' => $i2['stock']
                    ];
                    return $c2;
                }, [])
            ];
            return $c1;
        }, []);

        return $data;
    }


    public function actionUpdate()
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        $stock1c = $bodyParams['stocks'];
        $stockCompare = [];

        $stockDb = Stock::find()
            ->indexBy(function ($row) {
                return $row['warehouse_id'] . ';' . $row['product_id'] . ';' . $row['size_id'];
            })
            ->asArray()
            ->all();
        $warehouses = Warehouse::find()
            ->indexBy('1c_id')
            ->asArray()
            ->all();
        $products = Product::find()
            ->where(['not', ['1c_id' => null]])
            ->indexBy('1c_id')
            ->asArray()
            ->all();
        $productSizes = ProductSize::find()
            ->where(['not', ['1c_id' => null]])
            ->indexBy('1c_id')
            ->asArray()
            ->all();

        try {
//            Stock::deleteAll();

            foreach ($stock1c as &$s) {
                if (!array_key_exists($s['warehouse_1c_id'], $warehouses)
                    || !array_key_exists($s['product_1c_id'], $products)
                    || !array_key_exists($s['size_1c_id'], $productSizes))
                    continue;
                $warehouseId = $warehouses[$s['warehouse_1c_id']]['warehouse_id'];
                $productId = $products[$s['product_1c_id']]['product_id'];
                $sizeId = $productSizes[$s['size_1c_id']]['size_id'];
                $idx = $warehouseId . ';' . $productId . ';' . $sizeId;
                if (!array_key_exists($idx, $stockDb)) {
                    //create
                    $model = new Stock();
                    $model->warehouse_id = $warehouseId;
                    $model->product_id = $productId;
                    $model->size_id = $sizeId;
                } elseif ($s['stock'] != $stockDb[$idx]['stock']) {
                    //update
                    $model = Stock::find()
                        ->where([
                            'warehouse_id' => $warehouseId,
                            'product_id' => $productId,
                            'size_id' => $sizeId,
                        ])
                        ->one();
                } else {
                    continue;
                }
                $model->stock = $s['stock'];
                $model->save(false);
                $stockCompare[$idx] = $idx;
            }
            foreach ($stockDb as $s) {
                if (!array_key_exists($s['warehouse_id'] . ';' . $s['product_id'] . ';' . $s['size_id'], $stockCompare)) {
                    //update with 0
                    $model = Stock::find()
                        ->where([
                            'warehouse_id' => $s['warehouse_id'],
                            'product_id' => $s['product_id'],
                            'size_id' => $s['size_id'],
                        ])
                        ->one();
                    if ($model->stock != 0) {
                        $model->stock = 0;
                        $model->save(false);
                    }
                }
            }

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

    public function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }

}