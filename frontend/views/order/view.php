<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Заказ № ' . Yii::$app->request->get()['order_id'] . ' (' . $order['status'] . ')';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($isPaid): ?>
        <div class="has-text-success has-text-weight-bold">Оплачен</div>
    <?php endif; ?>
    <div class="has-text-weight-bold">Итого: <span
                class="has-text-weight-normal"><?= Yii::$app->formatter->asDecimal($order['sum']) ?></span></div>
    <div class="has-text-weight-bold">ТТН: <span class="has-text-weight-normal"><?= $order['ttn'] ?></span></div>
    <div class="columns is-vcentered has-text-weight-bold">
        <div class="column is-2-widescreen"></div>
        <div class="column">Наименование</div>
        <div class="column">
            <div class="columns is-vcentered is-mobile">
                <div class="column">Размер</div>
                <div class="column">Количество</div>
                <div class="column">Цена</div>
                <div class="column">Сумма со скидкой</div>
            </div>
        </div>
    </div>
    <?php foreach ($order['products'] as $op): ?>
        <div class="columns is-vcentered product-list">
            <div class="column is-1">
                <a href="<?= Url::to(['product/index', 'product_id' => $op['product']->product_id, 's1' => $op['product']->url, 's2' => 'p']) ?>"
                   title="title="<?= $op['product']->productDesc->name; ?>"
                <figure class="image"><img src="/<?= $op['product']->getImage()->getPath('200x'); ?>"
                                           alt="<?= $op['product']->productDesc->name; ?>"</figure>
                </a>
            </div>
            <div class="column has-text-centered-mobile">
                <a href="<?= Url::to(['product/index', 'product_id' => $op['product']->product_id, 's1' => $op['product']->url, 's2' => 'p']) ?>"><?= $op['product']->productDesc->name; ?></a>
            </div>
            <div class="column">
                <div class="columns is-vcentered is-mobile">
                    <div class="column"><?= $op['size_name']; ?></div>
                    <div class="column"><?= $op['amount']; ?></div>
                    <div class="column"><?= Yii::$app->formatter->asDecimal($op['price']); ?></div>
                    <div class="column"><?= Yii::$app->formatter->asDecimal($op['sum']); ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="columns is-vcentered">
        <div class="column is-2-widescreen is-hidden-mobile"></div>
        <div class="column is-hidden-mobile"></div>
        <div class="column">
            <div class="columns is-vcentered is-mobile">
                <div class="column"></div>
                <div class="column has-text-weight-bold"><?= $order['amount']; ?></div>
                <div class="column"></div>
                <div class="column has-text-weight-bold"><?= Yii::$app->formatter->asDecimal($order['sum']); ?></div>
            </div>
        </div>
    </div>
    <div><?= Html::encode($order['comment']) ?></div>
    <?php if ($isShipped && !$isPaid): ?>
        <?php $form = ActiveForm::begin([
            'id' => 'payment-form',
//            'enableAjaxValidation' => true,
        ]); ?>
        <?php if ($paymentForm->bonus): ?>
            <div>Вы можете оплатить бонусами не более 70% суммы заказа.</div>
            <label class="label">Оплатить бонусами (поле должно быть заполнено)</label>
            <div class="field">
                <div class="control">
                    <?= $form->field($paymentForm, 'bonus')
                        ->textInput(['autofocus' => true, 'placeholder' => 'Оплатить бонусами', 'class' => 'input is-primary', 'type' => 'number'])
                        ->label(false) ?>
                </div>
            </div>
            <label class="label">Оплатить наличными (LiqPay)</label>
            <div class="field">
                <div class="control">
                    <?= $form->field($paymentForm, 'cash')
                        ->textInput(['autofocus' => true, 'placeholder' => 'Оплатить наличными', 'class' => 'input is-primary', 'type' => 'number'])
                        ->label(false) ?>
                </div>
            </div>
            <?= Html::submitButton('Оплатить', ['class' => 'button is-primary']) ?>
        <?php endif; ?>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>

