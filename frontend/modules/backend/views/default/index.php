<?php

use yii\helpers\Html;

$this->params['breadcrumbs'] = [];

?>
<div class="container content section">
    <div class="buttons">
        <button class="button is-primary">
            <?= Html::a('Товары', ['product/index'], ['class' => 'has-text-white']) ?>
        </button>
        <?php if (Yii::$app->user->getIdentity()->isAdmin()): ?>
            <button class="button is-primary">
                <?= Html::a('Импорт товаров', ['product/create'], ['class' => 'has-text-white']) ?>
            </button>
        <?php endif; ?>
        <button class="button is-primary">
            <?= Html::a('Клиенты', ['user/index'], ['class' => 'has-text-white']) ?>
        </button>
        <button class="button is-primary">
            <?= Html::a('Заказы', ['order/index'], ['class' => 'has-text-white']) ?>
        </button>
        <button class="button is-primary">
            <?= Html::a('Бонусы', ['bonus/index'], ['class' => 'has-text-white']) ?>
        </button>
        <button class="button is-primary">
            <?= Html::a('Рекламации', ['complaint/index'], ['class' => 'has-text-white']) ?>
        </button>
    </div>
</div>
