<?php

use yii\helpers\Html;
use yii\helpers\Url;

use frontend\assets\DeliveryAsset;
use yii\bootstrap\ActiveForm;

DeliveryAsset::register($this);
//$this->registerJsFile('/js/cart.js', ['depends' => [AppAsset::class]]);

$this->title = 'Заявка на рекламацию';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'complaint-form',
        'options' => [
            'class' => 'complait-form',
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    <div class="subtitle">Контактная информация</div>
    <div class="box">
        <div class="field control">
            <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Фамилия', 'class' => 'input is-primary'])->label(false) ?>
        </div>
        <div class="field control">
            <?= $form->field($model, 'first_name')->textInput(['placeholder' => 'Имя', 'class' => 'input is-primary'])->label(false) ?>
        </div>
        <div class="field control">
            <?= $form->field($model, 'middle_name')->textInput(['placeholder' => 'Отчество', 'class' => 'input is-primary'])->label(false) ?>
        </div>
        <div class="columns is-mobile">
            <div class="column is-one-quarter-tablet is-one-third-mobile">
                <div class="select field control">
                    <?= $form->field($model, 'phone_prefix')->dropDownList(
                        array_combine($model::$operators, $model::$operators),
                        ['class' => 'input is-primary']
                    )->label(false); ?>
                </div>
            </div>
            <div class="column">
                <div class="field control">
                    <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Телефон', 'class' => 'input is-primary', 'type' => 'number', 'maxLength' => 7])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="columns is-mobile">
            <div class="column is-one-quarter-tablet is-one-third-mobile">
                <div class="select field control">
                    <?= $form->field($model, 'phone_extra_prefix')->dropDownList(
                        array_combine($model::$operators, $model::$operators),
                        ['class' => 'input is-primary']
                    )->label(false); ?>
                </div>
            </div>
            <div class="column">
                <div class="field control">
                    <?= $form->field($model, 'phone_extra')->textInput(['placeholder' => 'Дополнительный телефон', 'class' => 'input is-primary'])->label(false) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="subtitle">Забрать на рекламацию</div>
    <div class="box">
        <div class="delivery__container">
            <div class="field control">
                <?= $form->field($model, 'delivery_service_id')->radioList(['1' => 'Новая Почта', '2' => 'Мист-Экспресс'], ['name' => 'delivery_serv']); ?>
            </div>
            <div class="field control">
                <?= $form->field($model, 'service_type')->radioList(['WarehouseWarehouse' => 'Отделение', 'WarehouseDoors' => 'Адрес',], ['class' => 'service-type']); ?>
            </div>
            <div class="field control">
                <?= $form->field($model, 'city')->textInput(['placeholder' => 'Нас. пункт (от 3 букв)', 'class' => 'input is-primary city', 'data-ref' => ''])->label(false) ?>
            </div>
            <div class="field control">
                <?= $form->field($model, 'warehouse')->textInput(['placeholder' => 'Отделение (выберите из списка)', 'class' => 'input is-primary warehouse'])->label(false) ?>
            </div>
            <div class="field control">
                <?= $form->field($model, 'street')->textInput(['placeholder' => 'Улица (от 3 букв)', 'class' => 'input is-primary street'])->label(false) ?>
            </div>
            <div class="field control">
                <?= $form->field($model, 'building')->textInput(['placeholder' => 'Дом', 'class' => 'input is-primary building'])->label(false) ?>
            </div>
            <div class="field control">
                <?= $form->field($model, 'flat')->textInput(['placeholder' => 'Квартира', 'class' => 'input is-primary flat'])->label(false) ?>
            </div>
            <?= $form->field($model, 'city_ref')->hiddenInput(['class' => 'city-ref'])->label(false) ?>
            <?= $form->field($model, 'warehouse_ref')->hiddenInput(['class' => 'warehouse-ref'])->label(false) ?>
            <?= $form->field($model, 'street_ref')->hiddenInput(['class' => 'street-ref'])->label(false) ?>
        </div>
    </div>

    <div class="field control">
        <?= $form->field($model, 'sameAddress')->checkbox(['uncheck' => false, 'value' => true, 'class' => 'same-address'])->label('После рекламации доставить на тот же адрес'); ?>
    </div>

    <div class="after-complaint">
        <div class="subtitle">Доставить после рекламации</div>
        <div class="box">
            <div class="delivery__container">
                <div class="field control">
                    <?= $form->field($model, 'delivery_service_id_to')->radioList(['1' => 'Новая Почта', '2' => 'Мист-Экспресс',], ['name' => 'delivery_serv_to']); ?>
                </div>
                <div class="field control">
                    <?= $form->field($model, 'service_type_to')->radioList(['WarehouseWarehouse' => 'Отделение', 'WarehouseDoors' => 'Адрес',], ['class' => 'service-type']); ?>
                </div>
                <div class="field control">
                    <?= $form->field($model, 'city_to')->textInput(['placeholder' => 'Нас. пункт (от 3 букв)', 'class' => 'input is-primary city'])->label(false) ?>
                </div>
                <div class="field control">
                    <?= $form->field($model, 'warehouse_to')->textInput(['placeholder' => 'Отделение (выберите из списка)', 'class' => 'input is-primary warehouse'])->label(false) ?>
                </div>
                <div class="field control">
                    <?= $form->field($model, 'street_to')->textInput(['placeholder' => 'Улица (от 3 букв)', 'class' => 'input is-primary street'])->label(false) ?>
                </div>
                <div class="field control">
                    <?= $form->field($model, 'building_to')->textInput(['placeholder' => 'Дом', 'class' => 'input is-primary building'])->label(false) ?>
                </div>
                <div class="field control">
                    <?= $form->field($model, 'flat_to')->textInput(['placeholder' => 'Квартира', 'class' => 'input is-primary flat'])->label(false) ?>
                </div>
                <?= $form->field($model, 'city_ref_to')->hiddenInput(['class' => 'city-ref'])->label(false) ?>
                <?= $form->field($model, 'warehouse_ref_to')->hiddenInput(['class' => 'warehouse-ref'])->label(false) ?>
                <?= $form->field($model, 'street_ref_to')->hiddenInput(['class' => 'street-ref'])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="subtitle">Выберите модель, размер, месяц и год покупки</div>
    <div class="box">

        <div class="columns">
            <div class="matras-box">
                <div>Матрасы</div>

                <div class="column">
                    <div class="select field control">
                        <?= $form->field($model, 'product_id')->dropDownList($products, ['class' => 'input is-primary product'])->label(false); ?>
                    </div>
                </div>
                <div class="column">
                    <div class="select field control">
                        <?= $form->field($model, 'size_id')->dropDownList([], ['class' => 'input is-primary size'])->label(false); ?>
                    </div>
                </div>

            </div>
        </div>
        <!--new  -->
        <hr>
        <div class="columns">
            <div class="matras-box">
                <div>Корпусная мебель</div>
                <div class="column cp_column">
                    <div class="select field control">
                        <?= $form->field($model, 'product_cm_id')->dropDownList($products_cm, ['class' => 'input is-primary product_cm'])->label(false); ?>
                    </div>
                </div>
                <div id='data_attrs'>Для отображения характеристик выберите товар
                </div>
            </div>
        </div>
        <hr>
        <!--new  -->
        <div class="columns is-mobile">
            <div class="column is-one-quarter-tablet">
                <div class="select field control">
                    <?= $form->field($model, 'purchase_month')->dropDownList(array_combine(range(1, 12), $months), ['class' => 'input is-primary'])->label(false); ?>
                </div>
            </div>
            <div class="column is-one-quarter-tablet">
                <div class="select field control">
                    <?= $form->field($model, 'purchase_year')->dropDownList(array_combine($years, $years), ['class' => 'input is-primary'])->label(false); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="field control">
        <?= $form->field($model, 'comment')->textInput(['placeholder' => 'Комментарий', 'class' => 'input is-primary']) ?>
    </div>

    <div class="subtitle">Обязательные фото</div>
    <div class="box">
        <p>Для оформления рекламации нужно предоставить перечень обязательных фото:</p>
        <ul>
            <li>- фото ШК (Штрих-код);</li>
            <li>- фото гарантийного талона (если нет ШК);</li>
            <li>- фото документа о приобретении товара;</li>
            <li>- фото основания кровати (ПОЛНОСТЬЮ), а не часть;</li>
            <li>- фото матраса с двух сторон (что бы убедится в том, что изделие не имеет загрязнений);</li>
        </ul>
        <p>Все остальные фото должны быть с конкретным браком, который был выявлен в процессе эксплуатации.</p>
        <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    </div>

    <?php if (false) : ?>
        <div class="cart__delivery">
            <div class="columns">
                <div class="column">
                    <?= $form->field($model, 'deliveryService')->radioList([
                        '1' => 'Новая Почта',
                        '2' => 'Мист-Экспресс',
                    ]); ?>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <?= $form->field($model, 'telephone')
                        ->textInput([
                            'autofocus' => true,
                            'placeholder' => 'Телефон',
                            'class' => 'input is-primary'
                        ])
                        ->label(false) ?>
                </div>
                <div class="column">
                    <?= $form->field($model, 'lastName')
                        ->textInput([
                            'placeholder' => 'Фамилия',
                            'class' => 'input is-primary'
                        ])
                        ->label(false) ?>
                </div>
                <div class="column">
                    <?= $form->field($model, 'firstName')
                        ->textInput([
                            'placeholder' => 'Имя',
                            'class' => 'input is-primary'
                        ])
                        ->label(false) ?>
                </div>
                <div class="column">
                    <?= $form->field($model, 'middleName')
                        ->textInput([
                            'placeholder' => 'Отчество',
                            'class' => 'input is-primary'
                        ])
                        ->label(false) ?>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <?= $form->field($model, 'serviceType')->radioList([
                        'WarehouseWarehouse' => 'Отделение',
                        'WarehouseDoors' => 'Адрес',
                    ]); ?>
                </div>
            </div>
            <?= $form->field($model, 'cityRef')->hiddenInput(['class' => 'city-ref'])->label(false) ?>
            <?= $form->field($model, 'streetRef')->hiddenInput(['class' => 'street-ref'])->label(false) ?>
            <?= $form->field($model, 'warehouseRef')->hiddenInput(['class' => 'warehouse-ref'])->label(false) ?>
            <div class="columns search__container">
                <div class="column is-one-quarter" data-search-handler="city">
                    <div class="control">
                        <?= $form->field($model, 'city')
                            ->textInput([
                                'placeholder' => 'Нас. пункт (от 3 букв)',
                                'class' => 'input is-primary search__city'
                            ])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="column is-one-quarter cart-form__item active cart-form__by-hand cart-form__warehouse" data-search-handler="warehouse">
                    <div class="control">
                        <?= $form->field($model, 'warehouse')
                            ->textInput([
                                'placeholder' => 'Отделение (от 3 букв)',
                                'class' => 'input is-primary search__warehouse'
                            ])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="column is-one-quarter cart-form__item cart-form__by-hand cart-form__doors" data-search-handler="street">
                    <div class="control">
                        <?= $form->field($model, 'street')
                            ->textInput([
                                'placeholder' => 'Улица (от 3 букв)',
                                'class' => 'input is-primary search__street'
                            ])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="column is-1 cart-form__item cart-form__by-hand cart-form__doors">
                    <?= $form->field($model, 'building')
                        ->textInput([
                            'placeholder' => 'Дом',
                            'class' => 'input is-primary'
                        ])
                        ->label(false) ?>
                </div>
                <div class="column is-1 cart-form__item">
                    <?= $form->field($model, 'flat')
                        ->textInput([
                            'placeholder' => 'Квартира',
                            'class' => 'input is-primary'
                        ])
                        ->label(false) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <button type="submit" class="button is-primary">Отправить</button>
    <?php ActiveForm::end(); ?>
</div>
<style>
    .product_cm {
        min-width: 130px;
    }

    .matras-box {
        display: flex;
        gap: 50px;
		 align-items: center;
		
        gap: 5px;
      
    }

    .attr-column {
		  align-items: center;
    gap: 5px;
        display: flex;
    }

    #data_attrs {
        display: flex;
        flex-wrap: wrap;
    }
	.attr-column .select {
    min-width: 70px;
}
</style>