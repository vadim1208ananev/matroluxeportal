<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, заполните следующие поля для авторизации.</p>
    <p>Обращаем внимание, что авторизация переделана на e-mail.</p>
    <p>Для демо-режима зайдите: e-mail <code>test@test.com</code> пароль <code>test</code></p>
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
    <div class="field">
        <label class="checkbox">
            <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'checkbox']) ?>
        </label>
    </div>
    <p>Если вы забыли пароль, вы
        можете <?= Html::a('его сбросить', ['site/request-password-reset'], ['class' => 'has-text-link']) ?>.</p>
    <p>Требуется новое письмо с
        подтверждением? <?= Html::a('Отправить', ['site/resend-verification-email'], ['class' => 'has-text-link']) ?>
        .</p>
    <p class="control">
        <?= Html::submitButton('Войти', ['class' => 'button is-primary', 'name' => 'login-button']) ?>
    </p>
    <?php ActiveForm::end(); ?>
</div>