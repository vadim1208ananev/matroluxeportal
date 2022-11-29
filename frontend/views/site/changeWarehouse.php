<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use common\models\User;

$this->registerJsFile('/js/main.js', ['depends' => [AppAsset::class]]);

$this->title = 'Выбор региона';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="columns">
        <div class="column is-one-quarter">
            <aside class="box menu">
                <ul class="menu-list menu-list-account">
                    <?php if (!Yii::$app->user->isGuest && !User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username)): ?>
                        <li><a href="<?= Url::to(['site/request-password-reset']) ?>">Восстановление пароля</a>
                        </li>
                    <?php endif; ?>
                    <li><a href="<?= Url::to(['site/change-warehouse']) ?>" class="is-active">Выбор региона</a>
                    <li><a href="<?= Url::to(['debt/index']) ?>">Задолженность</a></li>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="column">
            <p>Пожалуйста, выберите регион. В этом случае Вы будете видеть остатки на вашем региональном складе.</p>
            <?php $form = ActiveForm::begin(['id' => 'change-warehouse',]); ?>
            <div class="field">
                <div class="control">
                    <div class="select is-fullwidth">
                        <?= Html::activeDropDownList($model, 'warehouse_id', $warehouses, [
                            'prompt' => 'Выберите регион...',
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="control">
                <?= Html::submitButton('Сохранить', ['class' => 'button is-primary', 'name' => 'save-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
