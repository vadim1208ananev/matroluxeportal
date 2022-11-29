<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\OrderAsset;

OrderAsset::register($this);
$this->title = 'Заказы';

?>
<div class="container content section order-container">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="columns is-vcentered has-text-weight-bold">
        <div class="column">
            <div class="columns is-mobile">
                <div class="column">Номер</div>
                <div class="column">Номер 1С</div>
                <div class="column">Дата</div>
            </div>
        </div>
        <div class="column">
            <div class="columns is-mobile">
                <div class="column">Дата отгрузки</div>
                <div class="column">Сумма</div>
                <div class="column">Бонус</div>
                <div class="column">Статус</div>
            </div>
        </div>
        <div class="column">
            <div class="columns is-mobile">
                <div class="column">Комментарий</div>
            </div>
        </div>
        <div class="column">
            <div class="columns is-mobile">
                <div class="column">ТТН</div>
            </div>
        </div>
        <div class="column is-1"></div>
    </div>
    <?php foreach ($orders as $o): ?>
        <div class="columns is-vcentered order-list<?= $o['isPaid'] != null ? ' has-text-success' : '' ?>">
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column"><?= $o['order_id']; ?></div>
                    <div class="column"><?= $o['1c_number']; ?></div>
                    <div class="column"><?= date("d.m.Y", $o['created_at']); ?></div>
                </div>
            </div>
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column"><?= $o['shipped_at'] !== null ? date("d.m.Y", $o['shipped_at']) : ''; ?></div>
                    <div class="column"><?= Yii::$app->formatter->asDecimal($o['sum']); ?></div>
                    <div class="column"><?= $o['bonus'] ?></div>
                    <div class="column"><?= $o['status']; ?></div>
                </div>
            </div>
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column"><?= Html::encode($o['comment']) ?></div>
                </div>
            </div>
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column"><?= Html::encode($o['ttn']) ?></div>
                </div>
            </div>
            <div class="column is-pulled-right is-1">
                <a class="button is-primary is-outlined" title="Просмотреть"
                   href="<?= Url::to(['view', 'order_id' => $o['order_id']]); ?>">
                    <span class="icon"><i class="fas fa-eye"></i></span>
                </a>
                <a class="button is-primary is-outlined order-list_copy" title="Скопировать в корзину"
                   data-order-id="<?= $o['order_id'] ?>">
                    <span class="icon"><i class="fas fa-copy"></i></span>
                </a>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
</div>

