<?php

use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['action' => ['site/search'], 'method' => 'get', 'options' => ['class' => 'field is-grouped']]); ?>
<?= $form->field($model, 'q', [
    'options' => ['class' => 'control'],
    'inputOptions' => [
        'placeholder' => 'Найти',
        'class' => 'input control',
        'name' => 'q',
    ],
    'template' => '<p class="control">{input}</p>',
])
    ->label(false) ?>
<p class="control">
    <a class="button is-info">
        <span class="icon has-text-white"><i class="fas fa-search"></i></span>
    </a>
</p>
<?php ActiveForm::end(); ?>
