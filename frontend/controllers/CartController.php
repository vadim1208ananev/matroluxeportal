<?php

namespace frontend\controllers;

use common\models\CartForm;
use common\models\BonusOut;
use common\models\CategoryUnionOrder;
use common\models\Delivery;
use common\models\OrderProduct;
use common\models\OrderProductNostandart;
use common\models\Product;
use common\models\Size;
use common\models\User;
use common\services\delivery\DeliveryService;
use common\services\delivery\MeestExpress;
use common\services\delivery\NovaPostha;
use Yii;
use yii\helpers\Html;
use yii\httpclient\Client;
use yii\web\Controller;
use common\models\Order;
use yii\helpers\Url;

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
        $html = '';
        $request = Yii::$app->request;
        $modelCartForm = new CartForm();
        $modelCartForm->deliveryService = '1';
        $modelCartForm->serviceType = 'WarehouseWarehouse';
        $data['modelCartForm'] = $modelCartForm;

        if ($request->isPost && isset($request->post()['cart'])) {

            $cart = json_decode($request->post()['cart'], true);
            if (!$cart)
                return $html;
            $product = new Product();
            $products = $product::getCartSpecProducts($cart);
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
                $html .= "<a href='{$href}' title='{$pName}'<figure class='image'><img src='/{$imgSrc}' alt='{$pName}'</figure></a>";
                $html .= "</div>";

                $html .= "<div class='column has-text-centered-mobile'>";
                $html .= "<input class='input' type='hidden' name='categoryId' value='{$p['product']->category_id}'>";
                $html .= "<input class='input' type='hidden' name='productId' value='{$pId}'><a href='{$href}'>{$pName}</a>";
                $html .= "</div>";

                $html .= "<div class='column'>";
                $html .= "<div class='columns is-vcentered is-mobile'>";
                $html .= "<div class='column'><input class='input' type='hidden' name='sizeId' value='{$sId}'>{$sName}</div>";
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
        $data = $params = [];
        $request = Yii::$app->request;
        $modelCartForm = new CartForm();

        if (!Yii::$app->user->isGuest && User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)) {
            return "<div class='notification is-danger'><button class='delete'></button>В демо-режиме нельзя создать заказ!</div>";
        }

        if ($request->isPost) {
            $post = $request->post();

//            parse_str($post['form'], $params);
            $modelCartForm->load($post);
            if (!$modelCartForm->validate())
                if ($modelCartForm->hasErrors()) {
                    return json_encode([
                        'success' => false,
                        'data' => $modelCartForm->errors
                    ]);
                }

            $cart = $this->unionCartByCategory(json_decode($post['cart'], true));
            $userId = Yii::$app->user->getId();
            if ($cart) {
//                $userBonus = Yii::$app->user->getIdentity()->getBonus(); //TODO убрать позже комментарий
//                $bonus = $post['bonus'];
//                if ($bonus < 0 || $bonus > $userBonus)
//                    return json_encode([
//                        'success' => false,
//                        'html' => "<div class='notification is-danger'><button class='delete'></button>Возможная сумма оплаты бонусами {$userBonus} грн.</div>"
//                    ]);

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($cart as $item) {
                        $order = new Order();
                        $order->user_id = $userId;
                        $order->comment = Html::encode($post['comment']);
                        $order->save(false);
                        $lastInsertId = Yii::$app->db->getLastInsertID();
                        foreach ($item as $p) {
                            $orderProduct = Size::isStandart($p['sizeId']) ? new OrderProduct() : new OrderProductNostandart();
                            $orderProduct->order_id = $lastInsertId;
                            $orderProduct->product_id = $p['productId'];
                            $orderProduct->size_id = $p['sizeId'];
                            $orderProduct->amount = $p['amount'];
                            $orderProduct->save(false);
                        }

                        if ($modelCartForm->isDelivery == true) {
                            $delivery = new Delivery();
                            $delivery->delivery_service_id = intval($post['deliveryServiceId']);
                            $delivery->order_id = $lastInsertId;
                            $delivery->telephone = preg_replace('/[^0-9]/', '', $modelCartForm['telephone']);
                            $delivery->last_name = $modelCartForm['lastName'];
                            $delivery->first_name = $modelCartForm['firstName'];
                            $delivery->middle_name = $modelCartForm['middleName'];
                            $delivery->service_type = $modelCartForm['serviceType'];
                            $delivery->city = $modelCartForm['city'];
                            $delivery->city_ref = $post['cityRef'] != 'undefined' ? $post['cityRef'] : '';
                            $delivery->street = $modelCartForm['street'];
                            $delivery->street_ref = $post['streetRef'] != 'undefined' ? $post['streetRef'] : '';
                            $delivery->warehouse = $modelCartForm['warehouse'];
                            $delivery->warehouse_ref = $post['warehouseRef'] != 'undefined' ? $post['warehouseRef'] : '';
                            $delivery->building = $modelCartForm['building'];
                            $delivery->flat = $modelCartForm['flat'];
                            $delivery->save(false);
                        }

//                    if ($bonus > 0) { //TODO убрать позже комментарий
//                        $bonusOut = new BonusOut();
//                        $bonusOut->user_id = Yii::$app->user->id;
//                        $bonusOut->order_id = $lastInsertId;
//                        $bonusOut->bonus = intval(Html::encode($bonus));
//                        $bonusOut->save(false);
//
//                        $user = Yii::$app->user->getIdentity();
//                        $user->bonus = $user->calcBonus($user->id);
//                        $user->save(false);
//                    }
                    }

                    $transaction->commit();
                    return json_encode([
                        'success' => true,
                        'html' => "<div class='notification is-warning'><button class='delete'></button>Заказ создан!</div>"
                    ]);
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (Throwable $e) {
                    $transaction->rollBack();
                }
            }
        }
    }

    protected function getDeliveryService($deliveryServiceId)
    {
        switch ($deliveryServiceId) {
            case 1:
                return new NovaPostha();
                break;
            case 2:
                return new MeestExpress();
                break;
            default:
                return json_encode([
                    'success' => false,
                    'data' => 'Не выбрана служба доставки'
                ]);
        }
    }

    public function actionGetCities()
    {
        $deliveryService = $this->getDeliveryService(Yii::$app->request->post('deliveryServiceId', ''));
        return $deliveryService instanceof DeliveryService ? $deliveryService->getCities() : $deliveryService;
    }

    public function actionGetWarehouses()
    {
        $deliveryService = $this->getDeliveryService(Yii::$app->request->post('deliveryServiceId', ''));
        return $deliveryService instanceof DeliveryService ? $deliveryService->getWarehouses() : $deliveryService;
    }

    public function actionGetStreets()
    {
        $deliveryService = $this->getDeliveryService(Yii::$app->request->post('deliveryServiceId', ''));
        return $deliveryService instanceof DeliveryService ? $deliveryService->getStreets() : $deliveryService;
    }

    public function unionCartByCategory($cart)
    {
        $result = [];
        $categoryUnion = CategoryUnionOrder::find()
            ->indexBy('category_id_out')
            ->asArray()
            ->all();

        foreach ($cart as $item) {
            $key = array_key_exists($item['categoryId'], $categoryUnion) ? $categoryUnion[$item['categoryId']]['category_id_in'] : $item['categoryId'];
            $result[$key][] = $item;
        }
        return $result;
    }

}
