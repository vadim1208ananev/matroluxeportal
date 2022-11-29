<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Товары',
    ],
];

?>
<div class="section container content">
    <h1>Товары</h1>
    <div class="columns is-vcentered item-list">
        <div class="column">ИД</div>
        <div class="column">Наименование</div>
        <div class="column">Сортировка</div>
        <div class="column">Статус</div>
        <div class="column">Url</div>
        <div class="column">Бонус</div>
        <div class="column"></div>
    </div>
    <?php foreach ($products as $product): ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $product->product_id ?></div>
            <div class="column"><?= $product->name ?></div>
            <div class="column"><?= $product->sort_order ?></div>
            <div class="column"><?= $product->status ?></div>
            <div class="column"><?= $product->url ?></div>
            <div class="column"><?= $product->bonus ?></div>
            <div class="column">
                <button class="button is-primary">
                    <?= Html::a('Изменить', ['product/update', 'id' => $product->product_id], ['class' => 'has-text-white']) ?>
                </button>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
</div>
