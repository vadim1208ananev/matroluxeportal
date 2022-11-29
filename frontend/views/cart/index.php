<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use frontend\assets\CartAsset;
use yii\bootstrap\ActiveForm;

CartAsset::register($this);
//$this->registerJsFile('/js/cart.js', ['depends' => [AppAsset::class]]);

$this->title = 'Корзина';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!Yii::$app->user->isGuest): ?>
        <?php $form = ActiveForm::begin(['id' => 'cart-form', 'options' => ['class' => 'cart-form']]); ?>
        <div class="columns">
            <div class="column">
                <?= $form->field($modelCartForm, 'isDelivery')->checkbox([
                    'uncheck' => false,
                    'value' => true
                ])
                    ->label('Доставка перевозчиком (Новая Почта, Мист Эксресс) конечному потребителю'); ?>
            </div>
        </div>
        <div class="cart__delivery">
            <div class="columns">
                <div class="column">
                    <?= $form->field($modelCartForm, 'deliveryService')->radioList([
                        '1' => 'Новая Почта',
                        '2' => 'Мист-Экспресс',
                    ]); ?>
                </div>
            </div>            <div class="columns">
                <div class="column">
                    <?= $form->field($modelCartForm, 'telephone')
                        ->textInput([
                            'autofocus' => true,
                            'placeholder' => 'Телефон',
                            'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
                <div class="column">
                    <?= $form->field($modelCartForm, 'lastName')
                        ->textInput([
                            'placeholder' => 'Фамилия',
                            'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
                <div class="column">
                    <?= $form->field($modelCartForm, 'firstName')
                        ->textInput([
                            'placeholder' => 'Имя',
                            'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
                <div class="column">
                    <?= $form->field($modelCartForm, 'middleName')
                        ->textInput([
                            'placeholder' => 'Отчество',
                            'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <?= $form->field($modelCartForm, 'serviceType')->radioList([
                        'WarehouseWarehouse' => 'Отделение',
                        'WarehouseDoors' => 'Адрес',
                    ]); ?>
                </div>
            </div>
            <?= $form->field($modelCartForm, 'cityRef')->hiddenInput(['class' => 'city-ref'])->label(false) ?>
            <?= $form->field($modelCartForm, 'streetRef')->hiddenInput(['class' => 'street-ref'])->label(false) ?>
            <?= $form->field($modelCartForm, 'warehouseRef')->hiddenInput(['class' => 'warehouse-ref'])->label(false) ?>
            <div class="columns search__container">
                <div class="column is-one-quarter" data-search-handler="city">
                    <div class="control">
                        <?= $form->field($modelCartForm, 'city')
                            ->textInput([
                                'placeholder' => 'Нас. пункт (от 3 букв)',
                                'class' => 'input is-primary search__city'])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="column is-one-quarter cart-form__item active cart-form__by-hand cart-form__warehouse"
                     data-search-handler="warehouse">
                    <div class="control">
                        <?= $form->field($modelCartForm, 'warehouse')
                            ->textInput([
                                'placeholder' => 'Отделение (от 3 букв)',
                                'class' => 'input is-primary search__warehouse'])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="column is-one-quarter cart-form__item cart-form__by-hand cart-form__doors"
                     data-search-handler="street">
                    <div class="control">
                        <?= $form->field($modelCartForm, 'street')
                            ->textInput([
                                'placeholder' => 'Улица (от 3 букв)',
                                'class' => 'input is-primary search__street'])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="column is-1 cart-form__item cart-form__by-hand cart-form__doors">
                    <?= $form->field($modelCartForm, 'building')
                        ->textInput([
                            'placeholder' => 'Дом',
                            'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
                <div class="column is-1 cart-form__item">
                    <?= $form->field($modelCartForm, 'flat')
                        ->textInput([
                            'placeholder' => 'Квартира',
                            'class' => 'input is-primary'])
                        ->label(false) ?>
                </div>
            </div>
        </div>

        <!--
    <div class="buttons">
        <button type="submit"
                class="button is-warning ok-cart-order has-text-weight-bold"><? /*= Yii::t('app', 'Заказать'); */ ?></button>
        <button class="button is-warning ok-cart-delete-all"><? /*= Yii::t('app', 'Удалить'); */ ?></button>
    </div>
    -->
        <label class="label">Комментарий</label>
        <div class="field">
            <div class="control">
                <input class="input is-primary" type="text" name="comment" placeholder="Комментарий">
            </div>
        </div>
        <button type="submit" class="button is-primary product-cart_order">Заказать</button>
        <!--        <a class="button is-primary product-cart_order" title="Заказать">Заказать</a>-->
        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <p>Для заказа, пожалуйста, <a class="has-text-link" title="Войти" href="<?= Url::to(['site/login']) ?>">войдите
                в систему</a> или <a class="has-text-link" title="Зарегистрироваться"
                                     href="<?= Url::to(['site/signup']) ?>">зарегистрируйтесь</a>.</p>
    <?php endif; ?>
</div>
