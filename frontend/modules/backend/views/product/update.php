<?php

use yii\helpers\Html;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Бонусы',
    ],
];

?>
<div class="section container content">
    <h1><?= $product->name ?></h1>
    <?= $this->render('form', [
        'product' => $product,
        'productDesc' => $productDesc,
        'attrs' => $attrs,
        'productAttrsPost' => $productAttrsPost,
        'productSearch' => $productSearch,
        'nameButton' => 'Изменить',
    ]) ?>
</div>