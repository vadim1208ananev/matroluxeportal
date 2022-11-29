<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'form-signup',]); ?>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'companyname')
                ->textInput(['autofocus' => true, 'placeholder' => 'Название компании', 'class' => 'input is-primary'])
                ->label(false) ?>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'okpo')
                ->textInput(['autofocus' => true, 'placeholder' => 'ОКПО/ИНН', 'class' => 'input is-primary'])
                ->label(false) ?>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'username')
                ->textInput(['autofocus' => true, 'placeholder' => 'Имя', 'class' => 'input is-primary'])
                ->label(false) ?>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'email')
                ->textInput(['autofocus' => true, 'placeholder' => 'E-mail', 'class' => 'input is-primary'])
                ->label(false) ?>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <?= $form->field($model, 'password')
                ->passwordInput(['autofocus' => true, 'placeholder' => 'Пароль', 'class' => 'input is-primary'])
                ->label(false) ?>
        </div>
    </div>
    <div class="control">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'button is-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>