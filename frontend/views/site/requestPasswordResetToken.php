<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAsset;
use yii\helpers\Url;

$this->registerJsFile('/js/main.js', ['depends' => [AppAsset::class]]);

$this->title = 'Восстановление пароля';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="columns">
        <div class="column is-one-quarter">
            <aside class="box menu">
                <ul class="menu-list menu-list-account">
                    <li><a href="<?= Url::to(['site/request-password-reset']) ?>" class="is-active">Восстановление пароля</a>
                    </li>
                    <li><a href="<?= Url::to(['site/change-warehouse']) ?>">Выбор региона</a>
                    <li><a href="<?= Url::to(['debt/index']) ?>">Задолженность</a></li>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="column">
            <p>Пожалуйста, заполните вашу электронную почту. Ссылка для восстановления пароля будет отправлена туда.</p>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form',]); ?>
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
