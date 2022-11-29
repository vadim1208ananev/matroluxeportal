<?php

namespace api\controllers;

use common\models\Complaint;
use common\models\Post;
use common\rbac\Rbac;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ComplaintController extends ActiveController
{
    public $modelClass = 'common\models\Complaint';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

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
        //sites/api/complaints?date=20201225
        //sites/api/complaints
        $data = [];
        $formatter = Yii::$app->formatter;
        $get = Yii::$app->request->get();

        $user = Yii::$app->user;
        $isAdmin = $user->getIdentity()->isAdmin();

        //date
        if (isset($get['date'])) {
            $complaints = Complaint::find()
                ->joinWith('productSize')
                ->where(new Expression("from_unixtime(complaint.created_at, '%Y%m%d') =:date", [':date' => $get['date']]))
                ->all();
            foreach ($complaints as $c) {
                $data[] = [
                    'doc_id' => $c['complaint_id'],
                    'last_name' => $c['last_name'],
                    'first_name' => $c['first_name'],
                    'middle_name' => $c['middle_name'],
                    'phone_prefix' => $c['phone_prefix'],
                    'phone' => $c['phone'],
                    'phone_extra_prefix' => $c['phone_extra_prefix'],
                    'phone_extra' => $c['phone_extra'],
                    'delivery_service_id' => $c['delivery_service_id'],
                    'service_type' => $c['service_type'],
                    'city' => $c['city'],
                    'warehouse' => $c['warehouse'],
                    'street' => $c['street'],
                    'building' => $c['building'],
                    'flat' => $c['flat'],
                    'street_ref' => $c['street_ref'],
                    'city_ref' => $c['city_ref'],
                    'warehouse_ref' => $c['warehouse_ref'],
                    'delivery_service_id_to' => $c['delivery_service_id_to'],
                    'service_type_to' => $c['service_type_to'],
                    'city_to' => $c['city_to'],
                    'warehouse_to' => $c['warehouse_to'],
                    'street_to' => $c['street_to'],
                    'building_to' => $c['building_to'],
                    'flat_to' => $c['flat_to'],
                    'city_ref_to' => $c['city_ref_to'],
                    'warehouse_ref_to' => $c['warehouse_ref_to'],
                    'street_ref_to' => $c['street_ref_to'],
                    'comment' => $c['comment'],
                    'description' => $c['description'],
                    'product_name' => $c['product_name'],
                    'product_1c_id' => $c->product['1c_id'],
                    'size_name' => $c['size_name'],
                    'size_1c_id' => $c->productSize['1c_id'],
                    'purchase_month' => $c['purchase_month'],
                    'purchase_year' => $c['purchase_year'],
                    'created_at' => date('Ymd', $c['created_at']),
                    'updated_at' => date('Ymd', $c['created_at']),
                ];
            }
        }
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