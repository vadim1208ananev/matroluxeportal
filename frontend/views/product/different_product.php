<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\ProductAsset;

//use yii\bootstrap\ActiveForm;
use yii\widgets\ActiveForm;

ProductAsset::register($this);

$this->title = $p->productDesc->name;
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<div class="container content section">
    <div class="columns">
        <?php foreach ($p->getImages() as $k => $image): ?>
            <div class="column is-one-third">
                <figure class="image"><img src="/<?= $image->getPath('700x'); ?>"
                                           alt="<?= $p->productDesc->name; ?>"</figure>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <hr>

    <?php if (true || in_array(Yii::$app->user->id, [1, 32, 35, 62, 309])): ?>
        <?php $form = ActiveForm::begin([
            'id' => 'different-product-form',
            'enableAjaxValidation' => true,
            'action' => Url::to(['product/index', 'product_id' => $p->product_id, 's1' => $p->url, 's2' => 'p']),
            'options' => ['class' => 'wardrobe-form'],
        ]); ?>
        <?= $form->field($modelDifferentProductForm, 'productId')->hiddenInput(['value' => $p->product_id])->label(false); ?>
        <?= $form->field($modelDifferentProductForm, 'requiedFields')->hiddenInput(['value' => $requiedFields])->label(false); ?>

        <?php foreach ($attrs as $key => $item): ?>
            <div class="columns">
                <div class="column">
                    <?= $form->field($modelDifferentProductForm, $key)->radioList($item); ?>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
        <?= Html::submitButton('В корзину', ['class' => 'button is-primary different-product__cart']) ?>
        <?php ActiveForm::end(); ?>

    <?php else: ?>
        <p class="has-text-danger">По техническим причинам заказы мелкого корпуса отключены! Извините за временные
            неудобства!</p>
    <?php endif; ?>
</div>