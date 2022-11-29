<?php

namespace app\modules\backend\controllers;

use common\models\BonusIn;
use common\models\BonusOut;
use common\models\Order;
use common\models\User;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;

class OrderController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'backend']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = [];
        $get = Yii::$app->request->get();

        $data['date'] = isset($get['date']) ? date('d.m.Y', strtotime($get['date'])) : date('d.m.Y');
        $data['orders'] = Order::find()
            ->where(new Expression("from_unixtime(order.created_at, '%Y%m%d') =:date", [':date' => date('Ymd', strtotime($data['date']))]))
            ->orderBy('created_at ASC')
            ->all();
        return $this->render('index', $data);
    }

}
