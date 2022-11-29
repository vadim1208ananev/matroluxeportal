<?php

namespace frontend\controllers;

use common\models\OrderProduct;
use common\models\Product;
use common\models\Spec;
use common\models\SpecProduct;
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
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\helpers\Url;

/**
 * Site controller
 */
class SpecController extends Controller
{

    public function beforeAction($action)
    {
        if (in_array($action->id, ['index', 'create', 'view', 'delete', 'order', 'update', 'delete-current'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function checkControl($spec)
    {
        if (!$spec)
            throw new HttpException(404);

        if (!Yii::$app->user->can('viewSpec', ['doc' => $spec])) {
            throw new ForbiddenHttpException();
        }
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $data = [];
        $specs = [];
        $products = [];
        $html = '';

        $models = Spec::find()->where(['user_id' => Yii::$app->user->getId()])->orderBy('created_at DESC')->all();
        foreach ($models as $model) {
            $specs[] = [
                'spec_id' => $model->spec_id,
                'user_id' => $model->user_id,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
                'comment' => $model->comment
            ];
        }
        $data['specs'] = $specs;

        $request = Yii::$app->request;
        if ($request->isPost) {
            $spec = json_decode($request->post()['spec'], true);
            if (!$spec)
                return $html;
            $product = new Product();
            $products = $product::getCartSpecProducts($spec);

            foreach ($products as $p) {
                $href = Url::to(['product/index', 'product_id' => $p['product']->product_id, 's1' => $p['product']->url, 's2' => 'p']);
                $pName = $p['product']->productDesc->name;
                $imgSrc = $p['product']->getImage()->getPath('200x');
                $pId = $p['product']->product_id;
                $sId = $p['sizeId'];
                $sName = $p['sizeName'];
                $count = $p['count'];

                $html .= "<div class='columns is-vcentered product-list'>";

                $html .= "<div class='column is-1'>";
                $html .= "<a href='{$href}' title='{$pName}'<figure class='image is-64x64'><img src='/{$imgSrc}' alt='{$pName}'</figure></a>";
                $html .= "</div>";

                $html .= "<div class='column has-text-centered-mobile'>";
                $html .= "<input class='input' type='hidden' name='productId' value='{$pId}'><a href='{$href}'>{$pName}</a>";
                $html .= "</div>";

                $html .= "<div class='column'>";
                $html .= "<div class='columns is-vcentered is-mobile'>";
                $html .= "<div class='column'><input class='input' type='hidden' name='sizeId' value='{$sId}'>'{$sName}'</div>";
                $html .= "<div class='column'><div class='field'><input class='input' type='number' name='amount' value='{$count}'></div></div>";
                $html .= "</div></div>";

                $html .= "<div class='column is-pulled-right'><a class='button is-primary is-outlined product-cart_delete' title='Удалить'><span class='icon'><i class='fas fa-trash'></i></span></a></div>";
                $html .= "<div class='is-clearfix'></div>";

                $html .= "</div>";
            }
            return $html;
        }
        $data['products'] = $products;
        return $this->render('index', $data);
    }

    public function actionCreate()
    {
        $data = [];
        $request = Yii::$app->request;

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя создать спецификацию!</div>";
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($request->isPost) {
            $cart = json_decode($request->post()['spec'], true);
            $userId = Yii::$app->user->getId();
            if ($cart) {
                try {
                    $spec = new Spec();
                    $spec->user_id = $userId;
                    $spec->comment = Html::encode($request->post()['comment']);
                    $spec->save(false);
                    $lastInsertId = Yii::$app->db->getLastInsertID();
                    foreach ($cart as $p) {
                        $specProduct = new SpecProduct();
                        $specProduct->spec_id = $lastInsertId;
                        $specProduct->product_id = $p['productId'];
                        $specProduct->size_id = $p['sizeId'];
                        $specProduct->amount = $p['amount'];
                        $specProduct->save(false);
                    }
                    $transaction->commit();
                    return "<div class='notification is-warning'><button class='delete'></button>Спецификация создана!</div>";
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                }
            }
        }
    }

    public function actionView($spec_id)
    {
        $data = [];
        $products = [];
        $spec = Spec::findOne($spec_id);
        $this->checkControl($spec);

        $spec = Spec::find()
            ->joinWith([
                'specProducts' => function ($q) {
                    $q
                        ->with('product')
                        ->with('productDesc')
                        ->with('sizeDesc');
                }
            ])
            ->where(['spec.spec_id' => $spec_id])
            ->one();
        foreach ($spec->specProducts as $sp) {
            $products[] = [
                'product' => $sp->product,
                'product_id' => $sp->product_id,
                'size_id' => $sp->size_id,
                'amount' => intval($sp->amount),
                'product_name' => $sp->productDesc->name,
                'size_name' => $sp->sizeDesc->name
            ];
        }
        $data['spec'] = [
            'spec' => $spec->spec_id,
            'created_at' => $spec->created_at,
            'comment' => $spec['comment'],
            'products' => $products
        ];

        return $this->render('view', $data);
    }

    public function actionDelete($spec_id)
    {
        $spec = Spec::findOne($spec_id);
        $this->checkControl($spec);

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя удалять спецификацию!</div>";
        }

        try {
            $spec->delete();
        } catch (\Exception $e) {
            throw new $e;
        }
        return true;
    }

    public function actionDeleteCurrent($spec_id)
    {
        $request = Yii::$app->request;

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя удалять строки спецификации!</div>";
        }
        $data = json_decode($request->post('data'), true);
        $spec = Spec::findOne($spec_id);
        $this->checkControl($spec);
        $model = SpecProduct::find()
            ->where(['spec_id' => $data['specId'], 'product_id' => $data['productId'], 'size_id' => $data['sizeId']])
            ->one();
        if ($model) {
            try {
                $model->delete();
                return json_encode(['success' => true]);
            } catch (\Exception $e) {
                return json_encode(['success' => false]);
            }
        }
    }

    public function actionOrder($spec_id)
    {

        $spec = Spec::findOne($spec_id);
        $this->checkControl($spec);
        $request = Yii::$app->request;

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя создать заказ!</div>";
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($request->isPost) {
            $userId = Yii::$app->user->getId();
            try {
                $order = new Order();
                $order->user_id = $userId;
//                $order['1c_id'] = Uuid::uuid();
                $order->save(false);
                $lastInsertId = Yii::$app->db->getLastInsertID();
                foreach ($spec->specProducts as $p) {
                    $orderProduct = new OrderProduct();
                    $orderProduct->order_id = $lastInsertId;
                    $orderProduct->product_id = $p['product_id'];
                    $orderProduct->size_id = $p['size_id'];
                    $orderProduct->amount = $p['amount'];
                    $orderProduct->save(false);
                }
                $transaction->commit();
                return "<div class='notification is-warning' style='margin-top: 1em;'><button class='delete'></button>Заказ создан на основании спецификации!</div>";
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }
        }
    }

    public function actionUpdate($spec_id)
    {
        $request = Yii::$app->request;

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя редактировать спецификацию!</div>";
        }

        $data = json_decode($request->post('data'), true);
        $spec = Spec::findOne($spec_id);
        $this->checkControl($spec);
        $model = SpecProduct::find()
            ->where(['spec_id' => $data['specId'], 'product_id' => $data['productId'], 'size_id' => $data['sizeId']])
            ->one();
        if ($model) {
            $model->amount = $data['amount'];
            $model->save(false);
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => false]);


//        $spec = Spec::find()
//            ->joinWith([
//                'specProducts' => function ($q) {
//                    $q
//                        ->with('product')
//                        ->with('productDesc')
//                        ->with('sizeDesc');
//                }
//            ])
//            ->where(['spec.spec_id' => $spec_id])
//            ->one();
//        foreach ($spec->specProducts as $sp) {
//            $products[] = [
//                'product' => $sp->product,
//                'product_id' => $sp->product_id,
//                'size_id' => $sp->size_id,
//                'amount' => $sp->amount,
//                'product_name' => $sp->productDesc->name,
//                'size_name' => $sp->sizeDesc->name
//            ];
//        }
//        $data['spec'] = [
//            'spec' => $spec->spec_id,
//            'created_at' => $spec->created_at,
//            'comment' => $spec['comment'],
//            'products' => $products
//        ];
//
//        return $this->render('view', $data);
    }
}
