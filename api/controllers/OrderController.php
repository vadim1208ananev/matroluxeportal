<?php

namespace api\controllers;

use common\models\BonusIn;
use common\models\BonusOut;
use common\models\Order;
use common\models\OrderProduct;
use common\models\OrderProductNostandart;
use common\models\Post;
use common\models\Product;
use common\models\ProductSize;
use common\models\Size;
use common\models\UserHasFirstWardrobeOrder;
use common\rbac\Rbac;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;
use common\models\User;

class OrderController extends ActiveController
{
    public $modelClass = 'common\models\Order';

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
//        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;

    }

    public function actionIndex()
    {
        //sites/api/orders?date=20201225
        //sites/api/orders
        $data = [];
        $formatter = Yii::$app->formatter;
        $get = Yii::$app->request->get();

        $user = Yii::$app->user;
        $isAdmin = $user->getIdentity()->isAdmin();


        $root = Yii::getAlias('@webroot');
        $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/order_log.txt';
        if (is_file($log_file)) {

            $log_get = json_encode($get, JSON_UNESCAPED_UNICODE);
            $mess = date('Y-m-d H-i-s', time()) . " START REQUEST- $log_get , Success\n";
            file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
        };



        //date
        //todo total['amount'] с портала иногда бывает 0
        if (isset($get['date'])) {

            $bonusOuts = ArrayHelper::getColumn(BonusOut::find()
                ->select('order_id')
                ->where(new Expression("from_unixtime(bonus_out.created_at, '%Y%m%d') =:date", [':date' => $get['date']]))
                ->asArray()
                ->all(), 'order_id');

            $orders = Order::find()
                ->joinWith('user')
                ->joinWith('bonusOut')
                ->joinWith('delivery')
                ->joinWith([
                    'orderProducts' => function ($q) {
                        $q
                            ->joinWith('product')
                            ->joinWith('productSize')
                            ->joinWith('size');
                    }
                ])
                ->joinWith([
                    'orderProductNostandarts' => function ($q) {
                        $q
                            ->joinWith('product');
                    }
                ])
                ->where(new Expression("from_unixtime(order.created_at, '%Y%m%d') =:date", [':date' => $get['date']]))
                ->orWhere(['in', 'order.order_id', $bonusOuts])
                ->all();
            foreach ($orders as $o) {
                if (!$isAdmin) {
                    if (!Yii::$app->user->can('viewOrder', ['doc' => $o])) {
                        continue;
                    }
                }
                $products = [];
                foreach ($o->orderProducts as $op) {
                    $products[] = [
                        'product_id' => $op->product_id,
                        'product_1c_id' => $op->product['1c_id'],
                        'product_name' => $op->product['name'],
                        'size_1c_id' => $op->productSize['1c_id'],
                        'size_name' => $op->size['name'],
                        'amount' => $formatter->asInteger($op->amount)
                    ];
                }
                foreach ($o->orderProductNostandarts as $opn) {
                    $products[] = [
                        'product_id' => $opn->product_id,
                        'product_1c_id' => $opn->product['1c_id'],
                        'product_name' => $opn->product['name'],
                        'size_1c_id' => $opn->size_id,
                        'size_name' => $opn->size_id,
                        'amount' => $formatter->asInteger($opn->amount)
                    ];
                }
                $data[] = [
                    'order_1c_id' => $o['1c_id'],
                    'order_id' => $o->order_id,
                    'order_comment' => $o->comment,
                    'order_date' => date('Ymd', $o->created_at),
                    'user_id' => $o->user_id,
                    'user_name' => $o->user->username,
                    'user_email' => $o->user->email,
                    'bonus_out' => $o->bonusOut != null ? $o->bonusOut->bonus : 0,
                    'products' => $products,
                    'delivery' => $o->delivery != null ? ArrayHelper::toArray($o->delivery) : 0
                ];
            }


            $root = Yii::getAlias('@webroot');
            $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/order_log.txt';
            if (is_file($log_file)) {
    
                $log_get = json_encode($data, JSON_UNESCAPED_UNICODE);
                $mess = date('Y-m-d H-i-s', time()) . "END RESPONSE- $log_get , Success\n";
                file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
            };


            return $data;

//            //test
//            return [
//                [
//                    'order_1c_id' => null,
//                    'order_id' => 1139,
//                    'order_comment' => "",
//                    'order_date' => "20211019",
//                    'user_id' => 1,
//                    'user_name' => "Oleg",
//                    'user_email' => "oleg.valen.com@gmail.com",
//                    'bonus_out' => 0,
//                    'products' => [
//                        [
//                            'product_id' => 206,
//                            'product_1c_id' => "f0d3dc9f-3f76-11e7-ae83-18fb7baa207a",
//                            'product_name' => "Шафа-купе 1 КЛАСІК 2-х дв.(Н)",
//                            'size_1c_id' => "1 000, 400, 2 050, біле дерево, профіль 1, С86 КЛ, С86 КЛ",
//                            'size_name' => "1 000, 400, 2 050, біле дерево, профіль 1, С86 КЛ, С86 КЛ",
//                            'amount' => "1"
//                        ],
//                        [
//                            'product_id' => 207,
//                            'product_1c_id' => 'ef3eb508-3f79-11e7-ae83-18fb7baa207a',
//                            'product_name' => 'Шафа-купе 1 СТАНДАРТ 2-х дв.(Н)',
//                            'size_1c_id' => '1 400, 600, 2 300, біле дерево, бронза БАВАРІЯ, Б33 СТ, Pr500 СТ',
//                            'size_name' => '1 400, 600, 2 300, біле дерево, бронза БАВАРІЯ, Б33 СТ, Pr500 СТ',
//                            'amount' => "1"
//                        ],
//                        [
//                            'product_id' => 221,
//                            'product_1c_id' => '6c32c3e9-3f85-11e7-ae83-18fb7baa207a',
//                            'product_name' => 'Шафа-купе 3 СТАНДАРТ 3-х дв.(Н)',
//                            'size_1c_id' => '1 500, 600, 2 300, білий аляска, бронза БАВАРІЯ, Pr500 СТ, Pr047 СТ, Б2 СТ',
//                            'size_name' => '1 500, 600, 2 300, білий аляска, бронза БАВАРІЯ, Pr500 СТ, Pr047 СТ, Б2 СТ',
//                            'amount' => "1"
//                        ]
//                    ],
//                    'delivery' => 0
//                ]
//            ];

        }

        //without date
        $orders = Order::find()
            ->joinWith('user')
            ->joinWith([
                'orderProducts' => function ($q) {
                    $q
                        ->joinWith('product')
                        ->joinWith('productSize')
                        ->joinWith('size');
                }
            ])
            ->joinWith([
                'orderProductNostandarts' => function ($q) {
                    $q
                        ->joinWith('product');
                }
            ])
//            ->where(new Expression("from_unixtime(order.created_at, '%Y%m%d') =:date", [':date' => $get['date']]))
            ->where(['user_id' => $user->id])
            ->all();
        foreach ($orders as $o) {
            if (!$isAdmin) {
                if (!Yii::$app->user->can('viewOrder', ['doc' => $o])) {
                    continue;
                }
            }
            $products = [];
            foreach ($o->orderProducts as $op) {
                $products[] = [
                    'product_id' => $op->product_id,
                    'product_name' => $op->product['name'],
                    'size_id' => $op->size_id,
                    'size_name' => $op->size['name'],
                    'amount' => $op->amount,
                    'sum' => $op->sum
                ];
            }
            foreach ($o->orderProductNostandarts as $opn) {
                $products[] = [
                    'product_id' => $opn->product_id,
                    'product_name' => $opn->product['name'],
                    'size_id' => $opn->size_id,
                    'size_name' => $opn->size_id,
                    'amount' => $formatter->asInteger($opn->amount),
                    'sum' => $opn->sum
                ];
            }
            $data[] = [
                'user_id' => $o->user_id,
                'order_id' => $o->order_id,
//                'order_date' => date('Ymd', $o->created_at),
                '1c_number' => $o['1c_number'],
                'sum' => $o->sum,
                'created_at' => $o->created_at,
                'updated_at' => $o->updated_at,
                'sipped_at' => $o->shipped_at,
                'status' => $o->getStatus(),
                'ttn' => $o->ttn,
                'comment' => $o->comment,
                'products' => $products
            ];
        }

        $root = Yii::getAlias('@webroot');
        $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/order_log.txt';
        if (is_file($log_file)) {

            $log_get = json_encode($data, JSON_UNESCAPED_UNICODE);
            $mess = date('Y-m-d H-i-s', time()) . "END RESPONSE- $log_get , Success\n";
            file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
        };

        return $data;

    }

    public function actionUpdate($id)
    {
        $bonus = 0;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $bodyParams = Yii::$app->getRequest()->getBodyParams();

        $root = Yii::getAlias('@webroot');
        $log_file = dirname(dirname($root)) . '/frontend/runtime/logs/order_log_update.txt';
        if (is_file($log_file)) {
           $dl=['order_id'=>$id,'bp'=>$bodyParams];
            $log_get = json_encode($dl, JSON_UNESCAPED_UNICODE);
            $mess = date('Y-m-d H-i-s', time()) . " START REQUEST- $log_get , Success\n";
            file_put_contents($log_file, $mess, FILE_APPEND | LOCK_EX);
        };

        try {
            $model = Order::findOne($id);
            if ($model)
                $user = User::findOne($model->user_id);
            if (!$model) {
                $model = Order::find()->where(['1c_id' => $id])->one();
                if (!$model) {
                    $model = new Order();
                }
                if ($model) {
                    $user = User::find()->where(['email' => $bodyParams['email']])->one();
                }
            }
            if (!$model)
                throw new ServerErrorHttpException('Order not found.');
            if (!$user)
                throw new ServerErrorHttpException('User not found.');

            $isPromotion1802_2102 = ($model['created_at'] >= strtotime('20220218')
                && $model['created_at'] <= strtotime('20220221'));
            $promotionProducts = [421, 422, 423, 424, 425, 426, 427, 428, 429, 430, 431, 432, 433, 434, 435,];

            $model['1c_number'] = $bodyParams['number'];
            $model['sum'] = $bodyParams['sum'];
            $model['shipped_at'] = strtotime($bodyParams['shipped_at']);
            $model['1c_id'] = $bodyParams['order_1c_id'];
            $model['user_id'] = $user->id;
            $status = $bodyParams['status'];
            //1 - У менеджера
            //2 - Обработан
            //3 - В производстве
            //4 - Отгружен
            if (!$status == 0) {
                $model['status'] = $status; //1,2,3,4
            }
            if ($bodyParams['ttn'])
                $model['ttn'] = $bodyParams['ttn'];

            if ($model->save(false) === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }

            $products = $bodyParams['products'];

            foreach ($products as $key => &$p) {
                $product = Product::findOne(['1c_id' => $p['product_1c_id']]);
                if ($product == null) {
                    unset($products[$key]); //на портале нет product по 1c_id
                    continue;
                }
                $p['product'] = $product;
            }

            foreach ($products as &$p) {
                $product = $p['product'];

                if (Size::isUuid($p['size_1c_id'])) {
                    $p['isStandart'] = true;
                    $sizeId = $this->getSizeId($p['double_packing'], $p['size_name'], $product->product_id, $p['size_1c_id']);
                    $op = OrderProduct::findOne([
                        'order_id' => $model->order_id,
                        'product_id' => $product->product_id,
                        'size_id' => $sizeId
                    ]);
                    if ($op && (($op->amount != $p['amount']) || ($op->price != $p['price']) || ($op->sum != $p['sum']))) {
                        $op->amount = $p['amount'];
                        $op->price = $p['price'];
                        $op->sum = $p['sum'];
                        $op->save(false);
                    }
                } else { //nostandart
                    $p['isStandart'] = false;
                    $sizeId = $p['size_1c_id'];
                    $opn = OrderProductNostandart::findOne([
                        'order_id' => $model->order_id,
                        'product_id' => $product->product_id,
                        'size_id' => $sizeId
                    ]);
                    if ($opn && (($opn->amount != $p['amount']) || ($opn->price != $p['price']) || ($opn->sum != $p['sum']))) {
                        $opn->amount = $p['amount'];
                        $opn->price = $p['price'];
                        $opn->sum = $p['sum'];
                        $opn->save(false);
                    }
                }

                if ($isPromotion1802_2102 && in_array($product->product_id, $promotionProducts)) {
                    $bonus += $p['sum'] * 0.05 / 100;
                } else {
                    $bonus += $p['sum'] * $product->bonus / 100;
                }

                $p['order_id'] = $model->order_id;
                $p['product_id'] = $product->product_id;
                $p['size_id'] = $sizeId;
                $p['product'] = $product;

            }

            $orderProducts = OrderProduct::find()
                ->where(['order_id' => $model->order_id])
                ->asArray()
                ->all();

            $orderProductNostandarts = OrderProductNostandart::find()
                ->where(['order_id' => $model->order_id])
                ->asArray()
                ->all();

            //анализ того, что есть в заказе на сайте, но удалили в 1с
            foreach ($orderProducts as $value) {
                foreach ($products as $v) {
                    if (!$v['isStandart'])
                        continue;
                    if ($value['order_id'] == $v['order_id']
                        && $value['product_id'] == $v['product_id']
                        && $value['size_id'] == $v['size_id']) {
                        continue 2;
                    }
                }
                $op = OrderProduct::findOne([
                    'order_id' => $value['order_id'],
                    'product_id' => $value['product_id'],
                    'size_id' => $value['size_id']
                ]);
                if ($op)
                    $op->delete();
            }

            foreach ($orderProductNostandarts as $value) {
                foreach ($products as $v) {
                    if ($v['isStandart'])
                        continue;
                    if ($value['order_id'] == $v['order_id']
                        && $value['product_id'] == $v['product_id']
                        && $value['size_id'] == $v['size_id']) {
                        continue 2;
                    }
                }
                $opn = OrderProductNostandart::findOne([
                    'order_id' => $value['order_id'],
                    'product_id' => $value['product_id'],
                    'size_id' => $value['size_id']
                ]);
                if ($opn)
                    $opn->delete();
            }

            //анализ того, что нет в заказе на сайте, но добавили в 1с
            foreach ($products as $value) {
                if ($value['isStandart']) {
                    foreach ($orderProducts as $v) {
                        if ($value['order_id'] == $v['order_id']
                            && $value['product_id'] == $v['product_id']
                            && $value['size_id'] == $v['size_id']) {
                            continue 2;
                        }
                    }
                    $sizeId = $this->getSizeId($value['double_packing'], $value['size_name'], $value['product_id'], $value['size_1c_id']);
                    if ($sizeId) {
                        $op = new OrderProduct();
                        $op->order_id = $value['order_id'];
                        $op->product_id = $value['product_id'];
                        $op->size_id = $sizeId;
                        $op->amount = $value['amount'];
                        $op->price = $value['price'];
                        $op->sum = $value['sum'];
                        $op->save(false);

                        if ($isPromotion1802_2102 && in_array($value['product_id'], $promotionProducts)) {
                            $bonus += $value['sum'] * 0.05 / 100;
                        } else {
                            $bonus += $value['sum'] * $value['product']->bonus / 100;
                        }

                    }
                } else {
                    foreach ($orderProductNostandarts as $v) {
                        if ($value['order_id'] == $v['order_id']
                            && $value['product_id'] == $v['product_id']
                            && $value['size_id'] == $v['size_id']) {
                            continue 2;
                        }
                    }
                    $op = new OrderProductNostandart();
                    $op->order_id = $value['order_id'];
                    $op->product_id = $value['product_id'];
                    $op->size_id = $value['size_1c_id'];
                    $op->amount = $value['amount'];
                    $op->price = $value['price'];
                    $op->sum = $value['sum'];
                    $op->save(false);

                    if ($isPromotion1802_2102 && in_array($value['product_id'], $promotionProducts)) {
                        $bonus += $value['sum'] * 0.05 / 100;
                    } else {
                        $bonus += $value['sum'] * $value['product']->bonus / 100;
                    }

                }
            }

            $update = false;
            $firstOrder = Order::find()->where(['user_id' => $user->getId()])->orderBy('created_at')->limit(1)->one();
            $hasFirstOrderShipped = $firstOrder->order_id == $model->order_id && $firstOrder->status == 4;
            $isBlackWeek = $model['created_at'] >= strtotime('20211122') && $model['created_at'] <= strtotime('20211129');

            $bonus = round($hasFirstOrderShipped
            || $this->userHasFirstWardrobeOrder($model)
            || $isBlackWeek
                ? $model->sum * 0.05 : $bonus);

            //начисленные бонусы
            if ($bonus > 0 && date('Ymd', $model['created_at']) >= '20210324' && $status == 4) {
                $bonusIn = BonusIn::findOne(['user_id' => $user->id, 'order_id' => $model->order_id]);
                if (!$bonusIn) {
                    $update = true;
                    $bonusIn = new BonusIn();
                    $bonusIn->user_id = $user->id;
                    $bonusIn->order_id = $model->order_id;
                    $bonusIn->bonus = $bonus;
                    $bonusIn->save(false);
                } else {
// not recalc bonus if status = 4 (shipped)
//                    if ($bonusIn->bonus != $bonus) {
//                        $update = true;
//                        $bonusIn->bonus = $bonus;
//                        $bonusIn->save(false);
//                    }
                }
            }

            //списанные бонусы
            //комментирую, т.к. BonusOut уже есть и бонус посчитан на уже отгруженном заказе
//            $bonusPaid = round($bodyParams['bonus_out']);
//                if ($bonusPaid > 0) {
//                    $bonusOut = BonusOut::findOne(['user_id' => $user->id, 'order_id' => $model->order_id]);
//                    if (!$bonusOut) {
//                        $update = true;
//                        $bonusOut = new BonusOut();
//                        $bonusOut->user_id = $user->id;
//                        $bonusOut->order_id = $model->order_id;
//                        $bonusOut->bonus = $bonusPaid;
//                        $bonusOut->save(false);
//                    } else {
//                        if ($bonusOut->bonus != $bonusPaid) {
//                            $update = true;
//                            $bonusOut->bonus = $bonusPaid;
//                            $bonusOut->save(false);
//                        }
//                    }
//                }

            if ($update) {
                $user->bonus = $user->calcBonus($user->id);
                $user->save(false);
            }


            $transaction->commit();
        } catch
        (Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return [
            'status' => true
        ];
    }

    public function getSizeId($doublePacking, $sizeName, $productId, $size1cId)
    {
        if ($doublePacking == 1) {
            //если двойная упаковка, то ищем по наименованию
            $size = Size::findOne(['name' => $sizeName]);
            $sizeId = $size ? $size->size_id : 0;
        } else {
            $productSize = ProductSize::findOne([
                '1c_id' => $size1cId,
                'product_id' => $productId,
            ]);
            $sizeId = $productSize ? $productSize->size_id : 0;
        }
        return $sizeId;
    }

    private function userHasFirstWardrobeOrder($order)
    {
        if ($order->created_at < strtotime('20220204'))
            return false;
        if (UserHasFirstWardrobeOrder::find()->where(['user_id' => Yii::$app->user->id])->exists())
            return false;

        foreach ($order->orderProductNostandarts as $item) {
            if ($item->product->category_id == 3) {
                $model = new UserHasFirstWardrobeOrder();
                $model->user_id = Yii::$app->user->id;
                $model->save(false);
                return true;
            }
        }
        return false;
    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }

}