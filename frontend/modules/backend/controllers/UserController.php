<?php

namespace app\modules\backend\controllers;

use common\models\BonusIn;
use common\models\BonusOut;
use common\models\Order;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'order', 'bonus'],
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

        $data['users'] = User::find()
            ->where(['is not', 'okpo', null])
            ->orderBy('created_at DESC')
            ->all();

        return $this->render('index', $data);
    }

    public function actionOrder($user_id)
    {
        $data = [];
        $data['user'] = User::findOne($user_id);
        $data['orders'] = Order::find()
            ->where(['user_id' => $user_id])
            ->orderBy('created_at DESC')
            ->all();

        return $this->render('order', $data);
    }

    public function actionBonus($user_id)
    {
        $data = $data['orders'] = [];
        $data['orders']['totalSum'] = $data['orders']['totalBonusIn'] = $data['orders']['totalBonusOut'] = 0;
        $data['user'] = User::findOne($user_id);
        $orderIds = array_merge(
            ArrayHelper::getColumn(BonusIn::find()
                ->select('order_id')
                ->where(['user_id' => $user_id])
                ->asArray()
                ->all(), 'order_id'),
            ArrayHelper::getColumn(BonusOut::find()
                ->select('order_id')
                ->where(['user_id' => $user_id])
                ->asArray()
                ->all(), 'order_id'));

        $orders = Order::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['in', 'order_id', $orderIds])
            ->with('bonusIn', 'bonusOut')
            ->orderBy('created_at DESC')
            ->all();

        foreach ($orders as $item) {
            $data['orders']['orders'][] = [
                'order_id' => $item->order_id,
                'created_at' => $item->created_at,
                'shipped_at' => $item->shipped_at,
                'sum' => $item->sum,
                '1c_number' => $item['1c_number'],
                'bonusIn' => $item->bonusIn ? $item->bonusIn->bonus : '',
                'bonusOut' => $item->bonusOut ? $item->bonusOut->bonus : '',
            ];
            $data['orders']['totalSum'] += $item->sum;
            $data['orders']['totalBonusIn'] += $item->bonusIn ? $item->bonusIn->bonus : 0;
            $data['orders']['totalBonusOut'] += $item->bonusOut ? $item->bonusOut->bonus : 0;
        }

        return $this->render('bonus', $data);
    }

}
