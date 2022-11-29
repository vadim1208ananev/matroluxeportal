<?php

namespace frontend\controllers;

use common\models\OrderProduct;
use common\models\Product;
use common\models\User;
use Faker\Provider\Uuid;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Order;

/**
 * Site controller
 */
class CartController extends Controller
{

    public function beforeAction($action)
    {
        if (in_array($action->id, ['index', 'create'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $data = [];
        $products = [];
        $request = Yii::$app->request;
        if ($request->isPost) {
            $cart = json_decode($request->post()['cart'], true);
            if ($cart) {
                $product = new Product();
                $products = $product::getCartSpecProducts($cart);
            }
        }
        $data['products'] = $products;
        return $this->render('index', $data);
    }

    public function actionCreate()
    {
        $data = [];
        $request = Yii::$app->request;

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя создать заказ!</div>";
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($request->isPost) {
            $cart = json_decode($request->post()['cart'], true);
            $userId = Yii::$app->user->getId();
            if ($cart) {
                try {
                    $order = new Order();
                    $order->user_id = $userId;
                    $order->comment = Html::encode($request->post()['comment']);
//                    $order['1c_id'] = Uuid::uuid();
                    $order->save(false);
                    $lastInsertId = Yii::$app->db->getLastInsertID();
                    foreach ($cart as $p) {
                        $orderProduct = new OrderProduct();
                        $orderProduct->order_id = $lastInsertId;
                        $orderProduct->product_id = $p['productId'];
                        $orderProduct->size_id = $p['sizeId'];
                        $orderProduct->amount = $p['amount'];
                        $orderProduct->save(false);
                    }
                    $transaction->commit();
                    return "<div class='notification is-warning'><button class='delete'></button>Заказ создан!</div>";
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                }
            }
        }
    }
}
