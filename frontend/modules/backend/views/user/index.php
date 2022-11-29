<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Клиенты',
    ],
];

?>
<div class="container content section">
    <h1>Клиенты</h1>
    <div class="columns is-vcentered item-list">
        <div class="column">Имя</div>
        <div class="column is-3">E-mail</div>
        <div class="column">Регистрация</div>
        <div class="column">Компания</div>
        <div class="column">Бонус</div>
        <div class="column">Заказы</div>
        <div class="column">Бонусы</div>
    </div>
    <?php foreach ($users as $user): ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $user->username ?></div>
            <div class="column is-3"><?= $user->email ?></div>
            <div class="column"><?= date("d.m.Y", $user->created_at); ?></div>
            <div class="column"><?= $user->companyname ?></div>
            <div class="column"><?= $user->bonus ?></div>
            <div class="column">
                <?= Html::a('Заказы', ['user/order', 'user_id' => $user->id], ['class' => 'is-primary button']) ?>
            </div>
            <div class="column">
                <?= Html::a('Бонусы', ['user/bonus', 'user_id' => $user->id], ['class' => 'is-primary button']) ?>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>

</div>
