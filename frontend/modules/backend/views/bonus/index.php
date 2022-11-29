<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Бонусы',
    ],
];

?>
<div class="container content section">
    <h1>Бонусы</h1>
    <div class="columns">
        <div class="column is-2">
            <?= DatePicker::widget([
                'name' => 'dp_1',
                'type' => DatePicker::TYPE_INPUT,
                'value' => $date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy',
                ],
                'options' => [
                    'class' => 'input datepicker_bonus',
                ],
            ]); ?>
        </div>
    </div>
    <h2>Начисленные бонусы</h2>
    <div class="columns is-vcentered item-list">
        <div class="column">ID</div>
        <div class="column">Создан</div>
        <div class="column">Дата отгрузки</div>
        <div class="column">Сумма</div>
        <div class="column">№ 1С</div>
        <div class="column">Бонус (+)</div>
    </div>
    <?php foreach ($ordersIn as $order): ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $order->order_id ?></div>
            <div class="column"><?= date("d.m.Y", $order->created_at) ?></div>
            <div class="column"><?= $order->shipped_at ? date("d.m.Y", $order->shipped_at) : '' ?></div>
            <div class="column"><?= $order->sum ?></div>
            <div class="column"><?= $order['1c_number'] ?></div>
            <div class="column"><?= $order->bonusIn ? $order->bonusIn->bonus : '' ?></div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
    <h2>Списанные бонусы + LiqPay</h2>
    <div class="columns is-vcentered item-list">
        <div class="column">ID</div>
        <div class="column">Создан</div>
        <div class="column">Дата отгрузки</div>
        <div class="column">Сумма</div>
        <div class="column">№ 1С</div>
        <div class="column">Бонус (-)</div>
        <div class="column">LiqPay</div>
        <div class="column">LiqPay (-2,75%)</div>
    </div>
    <?php foreach ($ordersOut as $order): ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $order->order_id ?></div>
            <div class="column"><?= date("d.m.Y", $order->created_at) ?></div>
            <div class="column"><?= $order->shipped_at ? date("d.m.Y", $order->shipped_at) : '' ?></div>
            <div class="column"><?= $order->sum ?></div>
            <div class="column"><?= $order['1c_number'] ?></div>
            <div class="column"><?= $order->bonusOut->bonus ?></div>
            <div class="column"><?= ($order->sum - $order->bonusOut->bonus) ?></div>
            <div class="column"><?= round(($order->sum - $order->bonusOut->bonus) * 0.9725, 2) ?></div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
</div>
