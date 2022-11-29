<?php

namespace frontend\controllers;

use common\models\BonusOut;
use common\models\Order;
use common\models\OrderPaid;
use common\models\PaymentForm;
use common\models\User;
use common\services\LiqPay;
use yii\helpers\Url;
use yii\httpclient\Client;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

/**
 * Site controller
 */
class OrderController extends Controller
{

    public function beforeAction($action)
    {
        if (in_array($action->id, ['copy', 'view'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function getStatus($status)
    {
        switch ($status) {
            case 1:
                return 'У менеджера';
            case 2:
                return 'Обработан';
            case 3:
                return 'В производстве';
            case 4:
                return 'Отгружен';
        }
    }

    /**
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $data = [];
        $orders = [];
        $models = Order::find()
            ->with(['orderPaid', 'bonusIn'])
            ->where(['user_id' => Yii::$app->user->getId()])
            ->orderBy('created_at DESC')
            ->all();
        foreach ($models as $model) {
            $orders[] = [
                'order_id' => $model->order_id,
                'user_id' => $model->user_id,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
                'shipped_at' => $model->shipped_at,
                'sum' => round($model->sum),
                '1c_number' => $model['1c_number'],
                'status' => $this->getStatus($model->status),
                'comment' => $model->comment,
                'ttn' => $model->ttn,
                'isPaid' => $model->orderPaid ? $model->orderPaid->paid : null,
                'bonus' => $model->bonusIn ? $model->bonusIn->bonus : null,
            ];
        }
        $data['orders'] = $orders;

        return $this->render('index', $data);
    }

    public function actionView($order_id)
    {
        $data = [];
        $products = [];
        $amount = 0;
        $liqpay = new LiqPay(Yii::$app->params['liqpay']['public_key'], Yii::$app->params['liqpay']['private_key']);

        $order = Order::findOne($order_id);
        if (!$order)
            throw new HttpException(404);

        $user = User::findOne($order->user_id);
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($post['data']) && isset($post['signature'])) {
            $response = $liqpay->api("/request", [
                'action' => 'status',
                'version' => '3',
                'order_id' => $order['1c_number']
            ]);
            $response = json_decode(json_encode($response), true);
//            Yii::info($response, 'debug');
            if ($liqpay->check_signature($post['data']) == $post['signature'] && $response['status'] == 'success') {
                $orderPaid = OrderPaid::findOne($order_id);
                if ($orderPaid) {
                    $orderPaid->paid = true;
                    $orderPaid->save();
                    if ($orderPaid->bonus > 0) {
                        $bonusOut = new BonusOut();
                        $bonusOut->user_id = $order->user_id;
                        $bonusOut->order_id = $order_id;
                        $bonusOut->bonus = intval($orderPaid->bonus);
                        $bonusOut->save(false);
                        $user->bonus = $user->calcBonus($user->id);
                        $user->save(false);
                    }
                }
            }
            Yii::$app->end();
        }

        if (!Yii::$app->user->can('viewOrder', ['doc' => $order])) {
            throw new ForbiddenHttpException();
        }

        $isShipped = $order->status == 4;
        $isPaid = $order->orderPaid ? $order->orderPaid->paid : null;

        $bonus = $user != null ? min($user->getBonus(), round($order->sum * Yii::$app->params['main.bonus_percent'] / 100)) : 0;
        $paymentForm = new PaymentForm($order->sum, $user->getBonus());
        $paymentForm->bonus = $bonus;
        $paymentForm->cash = $order->sum - $bonus;
        $data['paymentForm'] = $paymentForm;
        if (Yii::$app->request->isPost && $paymentForm->load($post) && $paymentForm->validate()) {
            if ($isShipped && !$isPaid) {

                $orderPaid = OrderPaid::findOne($order_id);
                if (!$orderPaid) {
                    $orderPaid = new OrderPaid();
                    $orderPaid->order_id = $order_id;
                }
                $orderPaid->sum = $order->sum;
                $orderPaid->bonus = $paymentForm->bonus;
                $orderPaid->cash = $paymentForm->cash;
                $orderPaid->save();

                if ($paymentForm->cash > 0) {
                    $params = [
                        'public_key' => Yii::$app->params['liqpay']['public_key'],
                        'private_key' => Yii::$app->params['liqpay']['private_key'],
                        'action' => 'pay',
                        'amount' => $paymentForm->cash,
                        'currency' => LiqPay::CURRENCY_UAH,
                        'description' => Yii::$app->params['liqpay']['description'],
                        'order_id' => ($order['1c_number'] == '322411' ? '322411_1' : $order['1c_number']),
                        'version' => '3',
                        'result_url' => Yii::$app->request->hostInfo . Url::current(),
                        'server_url' => Yii::$app->request->hostInfo . Url::current(),
                    ];

                    $client = new Client(['transport' => 'yii\httpclient\CurlTransport']); // only cURL supports the options we need
                    $response = $client->createRequest()
                        ->setMethod('post')
                        ->setUrl($liqpay->_checkout_url)
                        ->setData([
                            'data' => $liqpay->encode_params($params),
                            'signature' => $liqpay->cnb_signature($params)
                        ])
                        ->send();
                    if (isset($response->headers['location'])) {
                        $this->redirect($response->headers['location']);
                    }
                }

            }
        }

        //$order = Order::find()->where(['user_id' => Yii::$app->user->getId(), 'order_id' => $order_id])
        $order = Order::find()
            ->joinWith([
                'orderProducts' => function ($q) {
                    $q
                        ->with('product')
                        ->with('productDesc')
                        ->with('sizeDesc');
                }
            ])
            ->joinWith([
                'orderProductNostandarts' => function ($q) {
                    $q
                        ->with('product')
                        ->with('productDesc');
                }
            ])
            ->where(['order.order_id' => $order_id])
            ->one();
        foreach ($order->orderProducts as $op) {
            $products[] = [
                'product' => $op->product,
                'product_id' => $op->product_id,
                'size_id' => $op->size_id,
                'amount' => round($op->amount),
                'price' => round($op->price),
                'sum' => round($op->sum),
                'product_name' => $op->productDesc->name,
                'size_name' => $op->sizeDesc->name
            ];
            $amount += round($op->amount);
        }
        foreach ($order->orderProductNostandarts as $op) {
            $products[] = [
                'product' => $op->product,
                'product_id' => $op->product_id,
                'size_id' => $op->size_id,
                'amount' => round($op->amount),
                'price' => round($op->price),
                'sum' => round($op->sum),
                'product_name' => $op->productDesc->name,
                'size_name' => $op->size_id
            ];
            $amount += round($op->amount);
        }
        $data['order'] = [
            'order_id' => $order->order_id,
            'created_at' => $order->created_at,
            'shipped_at' => $order->shipped_at,
            'sum' => $order->sum,
            '1c_number' => $order['1c_number'],
            'status' => $this->getStatus($order->status),
            'products' => $products,
            'amount' => $amount,
            'comment' => $order->comment,
            'ttn' => $order->ttn,
        ];

        $data['isShipped'] = $isShipped;
        $data['isPaid'] = $isPaid;

        return $this->render('view', $data);
    }

    public function actionCopy()
    {
        $data = [];
        $request = Yii::$app->request;

        $order = Order::findOne($request->post('order-id'));
        if (!$order)
            throw new HttpException(404);

        if (!Yii::$app->user->can('viewOrder', ['doc' => $order])) {
            throw new ForbiddenHttpException();
        }

        foreach ($order->orderProducts as $item) {
            $data[$item->product_id][$item->size_id] = intval($item->amount);
        }

        return json_encode([
            'success' => true,
            'data' => json_encode($data),
            'html' => "<div class='notification is-danger'><button class='delete'></button>Заказ скопирован в корзину!</div>",
        ]);
    }


}
