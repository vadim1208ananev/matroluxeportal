<?php

namespace api\controllers;

use common\models\Debt;
use common\models\Order;
use common\models\Post;
use common\models\User;
use common\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class DebtController extends ActiveController
{
    public $modelClass = 'common\models\Debt';

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
        //sites/api/debts
        $data = [];
        $formatter = Yii::$app->formatter;
        $get = Yii::$app->request->get();
        $users = User::find()
            ->all();
        foreach ($users as $u) {
            $data[] = $u->email;
        }
        return $data;
    }

    public function actionUpdate()
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $bodyParams = Yii::$app->getRequest()->getBodyParams();
            $user = User::findOne(['email' => $bodyParams['email']]);
            if ($user) {
                Debt::deleteAll(['user_id' => $user->id]);

                foreach ($bodyParams['debts'] as $d) {
                    $order = Order::find()->
                    where(['1c_number' => $d['order_1c_number']])
                        ->one();
                    $debt = new Debt();
                    $debt->user_id = $user->id;
                    $debt->order_id = ($order !== null ? $order->order_id : '');
                    $debt['1c_number'] = $d['order_1c_number'];
                    $debt->created_at = strtotime($d['created_at']);
                    $debt->shipped_at = strtotime($d['shipped_at']);
                    $debt->debt = $d['debt'];
                    $debt->debt_overdue = $d['debt_overdue'];
                    $debt->days_overdue = $d['days_overdue'];
                    $debt->save(false);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
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