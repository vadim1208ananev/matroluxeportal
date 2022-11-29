<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use yii\widgets\LinkPager;
use common\models\User;

$this->registerJsFile('/js/main.js', ['depends' => [AppAsset::class]]);

$this->title = "Личный кабинет";
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="columns">
        <div class="column is-one-quarter">
            <aside class="box menu">
                <ul class="menu-list menu-list-account">
                    <?php if (!Yii::$app->user->isGuest && !User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)): ?>
                        <li><a href="<?= Url::to(['site/request-password-reset']) ?>">Восстановление пароля</a></li>
                    <?php endif; ?>
                    <li><a href="<?= Url::to(['site/change-warehouse']) ?>">Выбор региона</a></li>
                    <li><a href="<?= Url::to(['debt/index']) ?>">Задолженность</a></li>
                    <li><?= Html::a('Выход', ['site/logout'], [
                            'class' => 'navbar-item',
                            'title' => 'Выход',
                            'data' => ['method' => 'post']]) ?></li>
                </ul>
            </aside>
        </div>
        <div class="column">
            <p>Компания: <span class="has-text-weight-bold"><?= $user['companyname'] ?></span></p>
            <?php if ($user['manager']): ?>
                <p>Ваш менеджер: <span
                            class="has-text-weight-bold"><?= $user['manager'] ?>, <?= $user['manager_phone'] ?></span>
                </p>
            <?php endif; ?>
            <?php if ($user['retrobonus']): ?>
                <p>Ваш ретробонус на текущий месяц: <span
                            class="has-text-weight-bold"><?= $user['retrobonus'] ?>%</span></p>
            <?php endif; ?>
        </div>
    </div>
</div>
