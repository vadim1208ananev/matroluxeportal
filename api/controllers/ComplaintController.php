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

        $root = Yii::getAlias('@webroot');
        $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/complaint_log.txt';
        if (is_file($log_file)) {

            $log_get = json_encode($get, JSON_UNESCAPED_UNICODE);
            $mess = date('Y-m-d H-i-s', time()) . " START REQUEST- $log_get , Success\n";
            file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
        };
        // return dirname(dirname($root)).'/frontend/runtime/logs/complaint_log.txt';
        //return json_encode(get_defined_constants());
        //date
        if (isset($get['date'])) {
            $complaints = Complaint::find()
                ->joinWith('productSize')
                ->where(new Expression("from_unixtime(complaint.created_at, '%Y%m%d') =:date", [':date' => $get['date']]))
                ->andWhere(['is_send' => 1])
                ->all();
            foreach ($complaints as $c) {
                $product_data = [];
                $characteristics = [];
                if ($c['product_id'] && $c['size_id']) {
                    $characteristics = [[
                        'size_id' => $c['size_id'],
                        'size_name'=>$c->size->sizeDesc['name'],
                        'size_1c_id'=>$c->productSize['1c_id'],

                    ]];
                    $product_data = [
                        'product_id' => $c['product_id'],
                        'product_name' => $c->product->productDesc['name'],
                        'product_1c_id' => $c->product['1c_id'],
                        'characteristics' => $characteristics
                    ];
                }

                if ($c['product_cm_id'] && $c['attr_ids']) {
                    $characteristics = $c->arrtdata;
                    $product_data = [
                        'product_id' => $c['product_cm_id'],
                        'product_name' => $c->productcm->productDesc['name'],
                        'product_1c_id' => $c->productcm['1c_id'],
                        'characteristics' => $characteristics
                    ];
                }


                $data[] = [
                    'complaint_id' => $c['complaint_id'],
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
                    'complaint_type' => $c->type,
                    'city_ref_to' => $c['city_ref_to'],
                    'warehouse_ref_to' => $c['warehouse_ref_to'],
                    'street_ref_to' => $c['street_ref_to'],
                    'comment' => $c['comment'],
                    'description' => $c['description'],
                    'complaint_1c_id'=>$c['1c_id'],
                  //  'product_name' => $c['product_name'],
                  //  'product_1c_id' => $c->product['1c_id'],
                   // 'size_name' => $c['size_name'],
                   // 'size_1c_id' => $c->productSize['1c_id'],
                    'purchase_month' => $c['purchase_month'],
                    'purchase_year' => $c['purchase_year'],
                    'created_at' => date('Ymd', $c['created_at']),
                    'updated_at' => date('Ymd', $c['created_at']),
                    'product_data' => $product_data
                ];
            }
        }
        $root = Yii::getAlias('@webroot');
        $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/complaint_log.txt';
        if (is_file($log_file)) {

            $log_get = json_encode($data, JSON_UNESCAPED_UNICODE);
            $mess = date('Y-m-d H-i-s', time()) . "END RESPONSE- $log_get , Success\n";
            file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
        };
        return $data;
    }
    public function actionUpdate($id)
    {      
        $bodyParams = Yii::$app->getRequest()->getBodyParams();

        $root = Yii::getAlias('@webroot');
        $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/complaint_log_update.txt';
        if (is_file($log_file)) {
           $dl=['complaint_id'=>$id,'bp'=>$bodyParams];
            $log_get = json_encode($dl, JSON_UNESCAPED_UNICODE);
            $mess = date('Y-m-d H-i-s', time()) . " START REQUEST- $log_get , Success\n";
            file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
        };
        $model = Complaint::find()
                ->where(['complaint_id'=>$id])
                ->andWhere(['is_send' => 1])
                ->one();
        if (!$model||!$bodyParams['complaint_1c_id'])
       {
        return [
            'status' => false
        ];
       }
       $model['1c_id']=$bodyParams['complaint_1c_id'];
       $model->save();
       $root = Yii::getAlias('@webroot');
       $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/complaint_log_update.txt';
       if (is_file($log_file)) {
         
           $log_get = json_encode('ok', JSON_UNESCAPED_UNICODE);
           $mess = date('Y-m-d H-i-s', time()) . " END REQUEST- $log_get , Success\n";
           file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
       };
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
