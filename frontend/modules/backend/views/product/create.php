<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Товары',
    ],
];

?>
<div class="section container content">
    <h1>Импорт товаров</h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="columns">
        <div class="column">
            <div class="field">
                <div class="control">
                    <?= $form->field($product, 'textArea')->textArea(['class' => 'input primary', 'rows' => 10]) ?>
                </div>
            </div>
            <?= Html::submitButton('Импорт', ['class' => 'button is-primary']) ?>
        </div>
    </div>
    <?php $form = ActiveForm::end(); ?>
</div>
