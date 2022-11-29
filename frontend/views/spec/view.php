<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

$this->registerJsFile('/js/spec.js', ['depends' => [AppAsset::class]]);

$this->title = 'Спецификация № ' . Yii::$app->request->get()['spec_id'];
?>
<div class="container content section spec-list_container">
    <h1><?= Html::encode($this->title) ?></h1>
    <input type="hidden" name="specId" value="<?= Yii::$app->request->get()['spec_id'] ?>">
    <?php foreach ($spec['products'] as $sp): ?>
        <div class="columns is-vcentered product-list">
            <div class="column is-1">
                <a href="<?= Url::to(['product/index', 'product_id' => $sp['product']->product_id, 's1' => $sp['product']->url, 's2' => 'p']) ?>"
                   title="<?= $sp['product']->productDesc->name; ?>"
                <figure class="image is-64x64"><img src="/<?= $sp['product']->getImage()->getPath('200x'); ?>"
                                           alt="<?= $sp['product']->productDesc->name; ?>"</figure>
                </a>
            </div>
            <div class="column has-text-centered-mobile">
                <input class="input" type="hidden" name="productId"
                       value="<?= $sp['product_id']; ?>">
                <a href="<?= Url::to(['product/index', 'product_id' => $sp['product']->product_id, 's1' => $sp['product']->url, 's2' => 'p']) ?>"><?= $sp['product_name']; ?></a>
            </div>
            <div class="column">
                <div class="columns is-vcentered is-mobile">
                    <div class="column">
                        <input class="input" type="hidden" name="sizeId"
                               value="<?= $sp['size_id']; ?>"><?= $sp['size_name']; ?></div>
                    <div class="column">
                        <div class="field">
                            <input class="input" type="number" name="amount" value="<?= $sp['amount']; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-pulled-right">
                <a class="button is-primary is-outlined product-list_spec-cur__save" title="Записать">
                    <span class="icon"><i class="fas fa-save"></i></span>
                </a>
                <a class="button is-primary is-outlined product-list_spec-cur__delete" title="Удалить">
                    <span class="icon"><i class="fas fa-trash"></i></span>
                </a>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
    <a class="button is-primary spec_order" title="Создать заказ">Создать заказ</a>
</div>
