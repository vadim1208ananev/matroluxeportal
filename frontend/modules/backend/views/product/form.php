<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;

?>

<div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="columns">
        <div class="column">
            <div class="box">
                <p class="subtitle has-text-weight-bold">Картинки</p>
                <?= $form->field($product, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
            </div>

            <div class="box">
                <p class="subtitle has-text-weight-bold">Товар</p>
                <div class="field">
                    <div class="control">
                        <?= $form->field($product, 'name')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <?= $form->field($product, 'sort_order')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <?= $form->field($product, 'status')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <?= $form->field($product, 'url')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <?= $form->field($product, 'bonus')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <?= $form->field($productDesc, 'desc')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <?= $form->field($productSearch, 'search')->textInput(['class' => 'input primary']) ?>
                    </div>
                </div>
            </div>


            <div class="box">
                <p class="subtitle has-text-weight-bold">Фильтры</p>
                <?php foreach ($attrs as $k1 => $ag): ?>
                    <p class="subtitle"
                       style="margin-top: 1em; margin-bottom: 0.25em;"><?= $ag['attrGroupDesc']['name'] ?></p>
                    <div>
                        <?php foreach ($ag['attrs'] as $key => $a): ?>
                            <div style="display: inline-block; margin-right: 1em;">
                                <?php $attrId = $a['attr_id'] ?>
                                <?= $form->field($productAttrsPost[$attrId], "[$attrId]value")
                                    ->checkBox([
                                    ])->label($ag['attrDesc'][$key]['name']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!--            <div class="box">
                <p class="subtitle has-text-weight-bold">Product Option</p>
                <?php /*foreach ($modelPo as $index => $po): */ ?>
                    <div class="field">
                        <div class="control">
                            <? /*= $form->field($po, "[$index]value")->textInput([
                                'class' => 'input primary',
                                'template' => '{label}{input}\n{hint}\n{error}', //работает, только стили bulma перекрывают.. оставлю так
                            ])->label($po['attributeDescription']['name']) */ ?>
                        </div>
                    </div>
                <?php /*endforeach; */ ?>
            </div>
-->
            <div class="field">
                <div class="control">
                    <?= Html::submitButton($nameButton, ['class' => 'button is-primary is-medium']) ?>
                </div>
            </div>


        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
