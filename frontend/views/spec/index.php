<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

$this->registerJsFile('/js/spec.js', ['depends' => [AppAsset::class]]);

$this->title = 'Спецификации';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Спецификации - это "черновики" заказов. Создавайте спецификации, когда необходимо делать заказ с более-менее
        постоянным товарным составом. На основании спецификации можно создать заказ.</p>
    <hr>
    <h2>Созданные</h2>
    <div class="container content section spec-list_container">
        <div class="columns is-vcentered has-text-weight-bold">
            <div class="column is-two-thirds">
                <div class="columns is-mobile">
                    <div class="column">Номер</div>
                    <div class="column">Дата</div>
                    <div class="column">Комментарий</div>
                </div>
            </div>
            <div class="column"></div>
        </div>
        <?php foreach ($specs as $s): ?>
            <div class="columns is-vcentered spec-list" data-spec-id="<?= $s['spec_id']; ?>">
                <div class="column is-two-thirds">
                    <div class="columns is-mobile">
                        <div class="column"><?= $s['spec_id']; ?></div>
                        <div class="column" class="has-text-link"><?= date("d.m.Y", $s['created_at']); ?></div>
                        <div class="column"><?= $s['comment']; ?></div>
                    </div>
                </div>
                <div class="column is-pulled-right">
                    <a class="button is-primary is-outlined" title="Просмотреть"
                       href="<?= Url::to(['view', 'spec_id' => $s['spec_id']]); ?>">
                        <span class="icon"><i class="fas fa-eye"></i></span>
                    </a>
                    <a class="button is-primary is-outlined spec_delete" title="Удалить">
                        <span class="icon"><i class="fas fa-trash"></i></span>
                    </a>
                    <a class="button is-primary is-outlined spec_order" title="Создать заказ">
                        <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                    </a>
                </div>
                <div class="is-clearfix"></div>
            </div>
        <?php endforeach; ?>
    </div>
    <h2 class="current">Текущая</h2>
    <div class="field">
        <div class="control">
            <input class="input is-primary" type="text" name="comment" placeholder="Комментарий">
        </div>
    </div>
    <a class="button is-primary product-cart_spec" title="Создать спецификацию">Создать</a>
</div>
