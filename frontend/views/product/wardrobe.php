<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\ProductAsset;

//use yii\bootstrap\ActiveForm;
use yii\widgets\ActiveForm;

ProductAsset::register($this);

$this->title = $p->productDesc->name;
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<div class="container content section">
    <div class="columns">
        <?php foreach ($p->getImages() as $k => $image): ?>
            <div class="column is-one-third">
                <figure class="image"><img src="/<?= $image->getPath('700x'); ?>"
                                           alt="<?= $p->productDesc->name; ?>"</figure>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <hr>
    <?php if (false): ?>
        <ul>
            <?php foreach ($productAttrs as $pa): ?>
                <li><?= $pa->attrDesc->attr->attrGroup->attrGroupDesc->name; ?> - <?= $pa->attrDesc->name; ?></li>
            <?php endforeach; ?>
        </ul>
        <hr>
    <?php endif; ?>

    <?php if (true || in_array(Yii::$app->user->id, [1, 32, 35, 62, 309])): ?>
        <?php $form = ActiveForm::begin([
            'id' => 'wardrobe-form',
            'enableAjaxValidation' => true,
            'action' => Url::to(['product/index', 'product_id' => $p->product_id, 's1' => $p->url, 's2' => 'p']),
            'options' => ['class' => 'wardrobe-form'],
        ]); ?>
        <?= $form->field($modelWardrobeForm, 'productId')->hiddenInput(['value' => $p->product_id])->label(false); ?>
        <?= $form->field($modelWardrobeForm, 'numberOfDoors')->hiddenInput(['value' => $numberOfDoors])->label(false); ?>
        <div class="columns">
            <div class="column">
                <?= $form->field($modelWardrobeForm, 'width')->radioList($attrs['Ширина']); ?>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <?= $form->field($modelWardrobeForm, 'depth')->radioList($attrs['Глубина']); ?>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <?= $form->field($modelWardrobeForm, 'height')->radioList($attrs['Высота']); ?>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <?= $form->field($modelWardrobeForm, 'boardColor')->radioList($attrs['Цвет ДСП']); ?>
            </div>
        </div>

        <?php if (false): ?>
            <div class="columns">
                <div class="column">
                    <?= $form->field($modelWardrobeForm, 'profileColor')->radioList($attrs['Цвет профиля']); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="columns">
                <div class="column">
                    <div class="form-group field-wardrobedoorform-profilecolor required">
                        <label class="control-label">Система Бавария</label>
                        <input type="hidden" name="WardrobeDoorForm[profileColor]" value="">
                        <div id="wardrobedoorform-profilecolor" role="radiogroup" aria-required="true">
                            <?php foreach ($attrs['Система Бавария'] as $key => $item): ?>
                                <label><input type="radio" name="WardrobeDoorForm[profileColor]"
                                              value="<?= $key ?>"> <?= $item ?></label>
                            <?php endforeach; ?>
                            <br><br>
                            <div><label class="control-label">Цвет профиля</label></div>
                            <?php foreach ($attrs['Цвет профиля'] as $key => $item): ?>
                                <label><input type="radio" name="WardrobeDoorForm[profileColor]"
                                              value="<?= $key ?>"> <?= $item ?></label>
                            <?php endforeach; ?>
                        </div>
                        <div class="help - block"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <hr>

        <?php for ($i = 1; $i <= $numberOfDoors; $i++): ?>
            <div class="columns">
                <div class="column">
                    <div class="form-group field-wardrobedoorform-door<?= $i ?> required">
                        <label class="control-label">Дверь <?= $i ?></label>
                        <input type="hidden" name="WardrobeDoorForm[door<?= $i ?>]">
                        <div id="wardrobedoorform-door<?= $i ?>" class="wardrobedoorform-door" role="radiogroup"
                             aria-required="true">
                            <ul>
                                <li><span class="caret" data-id="<?= $parent['1c_id'] ?>"><?= $parent->name ?></span>
                                    <ul class="nested">
                                        <?php foreach ($children as $child): ?>
                                            <li><span class="caret" data-id="<?= $child['id'] ?>"
                                                      data-1c_id="<?= $child['1c_id'] ?>"
                                                      data-is-leaf="<?= ($child->rgt - $child->lft) == 1 ?>"><?= $child->name ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <p class="help-block"><?= array_key_exists('door' . $i, $errors) ? $errors['door' . $i][0] : '' ?></p>
                    </div>
                </div>
            </div>
            <?php if ($i == 1 && $numberOfDoors > 1): ?>
                <?= $form->field($modelWardrobeForm, 'sameDoors')->checkbox([
                    'uncheck' => false,
                    'value' => true,
                ])
                    ->label(false); ?>
                <br>
            <?php endif; ?>
            <hr>
        <?php endfor; ?>
        <?= Html::submitButton('В корзину', ['class' => 'button is-primary product__wardrobe-cart']) ?>
        <?php ActiveForm::end(); ?>

        <?php if (false): ?>
            <ul>
                <li><span class="caret">Двери нови</span>
                    <ul class="nested">
                        <li><span class="caret">Двери 2</span>
                            <ul class="nested">
                                <li><span class="caret">Двери 3</span>
                                    <ul class="nested">
                                        <li>Sencha</li>
                                        <li>Gyokuro</li>
                                        <li>Matcha</li>
                                        <li>Pi Lo Chun</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <p class="has-text-danger">По техническим причинам заказы корпусной мебели отключены! Извините за временные
            неудобства!</p>
    <?php endif; ?>
</div>