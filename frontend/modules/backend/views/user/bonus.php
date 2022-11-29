<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Клиенты',
        'url' => Url::toRoute('user/index')
    ],
    [
        'label' => $user->companyname,
    ],
];

?>
<div class="container content section">
    <h1>Бонусы: <?= $user->companyname . ' (' . $user->username . ')'; ?></h1>
    <div class="columns is-vcentered item-list">
        <div class="column">ID</div>
        <div class="column">Создан</div>
        <div class="column">Дата отгрузки</div>
        <div class="column">Сумма</div>
        <div class="column">№ 1С</div>
        <div class="column">Бонус (+)</div>
        <div class="column">Бонус (-)</div>
    </div>
    <?php foreach ($orders['orders'] as $order): ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $order['order_id'] ?></div>
            <div class="column"><?= date("d.m.Y", $order['created_at']) ?></div>
            <div class="column"><?= $order['shipped_at'] ? date("d.m.Y", $order['shipped_at']) : '' ?></div>
            <div class="column"><?= $order['sum'] ?></div>
            <div class="column"><?= $order['1c_number'] ?></div>
            <div class="column"><?= $order['bonusIn'] ?></div>
            <div class="column"><?= $order['bonusOut'] ?></div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
    <div class="columns is-vcentered item-list has-text-weight-bold">
        <div class="column"></div>
        <div class="column"></div>
        <div class="column"></div>
        <div class="column"><?= number_format($orders['totalSum'], '2', '.', '') ?></div>
        <div class="column"></div>
        <div class="column"><?= $orders['totalBonusIn'] ?></div>
        <div class="column"><?= $orders['totalBonusOut'] ?></div>
    </div>
</div>
