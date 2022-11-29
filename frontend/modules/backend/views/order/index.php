<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Заказы',
    ],
];

?>
<div class="container content section">
    <h1>Заказы</h1>
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
                    'class' => 'input datepicker_order',
                ],
            ]); ?>
        </div>
    </div>
    <div class="columns is-vcentered item-list">
        <div class="column">ID</div>
        <div class="column">Создан</div>
        <div class="column">Дата отгрузки</div>
        <div class="column">Сумма</div>
        <div class="column">№ 1С</div>
        <div class="column">Статус</div>
        <div class="column">Комментарий</div>
    </div>
    <?php foreach ($orders as $order): ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $order->order_id ?></div>
            <div class="column"><?= date("d.m.Y", $order->created_at) ?></div>
            <div class="column"><?= $order->shipped_at ? date("d.m.Y", $order->shipped_at) : '' ?></div>
            <div class="column"><?= $order->sum ?></div>
            <div class="column"><?= $order['1c_number'] ?></div>
            <div class="column"><?= $order->getStatus() ?></div>
            <div class="column"><?= $order->comment ?></div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
</div>
