<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAsset;
use yii\helpers\Url;

$this->registerJsFile('/js/main.js', ['depends' => [AppAsset::class]]);

$this->title = 'Отправить письмо для подтверждения';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="columns">
        <div class="column is-one-quarter">
            <aside class="menu">
                <p class="menu-label">Общее</p>
                <ul class="menu-list menu-list-account">
                    <li><a href="<?= Url::to(['site/request-password-reset']) ?>">Сброс пароля</a></li>
                    <li><a href="<?= Url::to(['site/reset-password']) ?>">reset-password</a></li>
                    <li><a href="<?= Url::to(['site/resend-verification-email']) ?>" class="is-active">resend-verification-email</a>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="column">
            <p>Пожалуйста, заполните вашу электронную почту для подтверждения е-мейл.</p>
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form',]); ?>
            <div class="field">
                <div class="control">
                    <?= $form->field($model, 'email')
                        ->textInput(['autofocus' => true, 'placeholder' => 'E-mail', 'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
            </div>
            <div class="control">
                <?= Html::submitButton('Отправить', ['class' => 'button is-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
