<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'login-form',]); ?>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'E-mail', 'class' => 'input is-primary'])->label(false) ?>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => 'Пароль', 'class' => 'input is-primary'])->label(false) ?>
        </div>
    </div>
    <p class="control">
        <?= Html::submitButton('Войти', ['class' => 'button is-primary', 'name' => 'login-button']) ?>
    </p>
    <?php ActiveForm::end(); ?>
</div>