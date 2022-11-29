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

class BonusController extends Controller
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

        $orderIds = ArrayHelper::getColumn(BonusIn::find()
            ->select('order_id')
            ->where(new Expression("from_unixtime(bonus_in.created_at, '%Y%m%d') =:date", [':date' => date('Ymd', strtotime($data['date']))]))
            ->asArray()
            ->all(), 'order_id');
        $data['ordersIn'] = Order::find()
            ->where(['in', 'order_id', $orderIds])
            ->with('bonusIn', 'bonusOut')
            ->orderBy('created_at ASC')
            ->all();

        $orderIds = ArrayHelper::getColumn(BonusOut::find()
            ->select('order_id')
            ->where(new Expression("from_unixtime(bonus_out.created_at, '%Y%m%d') =:date", [':date' => date('Ymd', strtotime($data['date']))]))
            ->asArray()
            ->all(), 'order_id');
        $data['ordersOut'] = Order::find()
            ->where(['in', 'order_id', $orderIds])
            ->with('bonusIn', 'bonusOut')
            ->orderBy('created_at ASC')
            ->all();

        return $this->render('index', $data);
    }

}
