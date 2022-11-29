<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Задолженность';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($pages->totalCount > 50): ?>
        <aside class="level">
            <div class="level-left">
                <div class="level-item">
                    <?= LinkPager::widget([
                        'pagination' => $pages,
                        'prevPageLabel' => '<',
                        'nextPageLabel' => '>',
                        'maxButtonCount' => 7,
                        'options' => ['class' => 'pagination category_pagination'],
                        'activePageCssClass' => 'pagination-link is-current',
                        'disableCurrentPageButton' => true,
                        'linkOptions' => ['class' => 'pagination-link'],
                    ]); ?>
                </div>
            </div>
        </aside>
    <?php endif; ?>

    <?php if ($debtsResult): ?>
        <div class="columns has-text-weight-bold is-mobile">
            <div class="column is-2-widescreen">Итого заказов</div>
            <div class="column is-2-widescreen">Общий долг</div>
            <!--<div class="column is-2-widescreen">Общий просроч. долг</div>-->
        </div>
        <?php foreach ($debtsResult as $d): ?>
            <div class="columns is-mobile">
                <div class="column is-2-widescreen"><?= $d['count']; ?></div>
                <div class="column is-2-widescreen"><?= Yii::$app->formatter->asDecimal($d['debt']); ?></div>
                <!--<div class="column is-2-widescreen"><?= Yii::$app->formatter->asDecimal($d['debt_overdue']); ?></div>-->
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>

    <div class="columns is-vcentered has-text-weight-bold">
        <div class="column">
            <div class="columns is-mobile">
                <div class="column">№ заказа</div>
                <div class="column">№ 1С</div>
                <div class="column">Дата заказа</div>
                <div class="column">Дата отгрузки</div>
            </div>
        </div>
        <div class="column">
            <div class="columns is-mobile">
                <div class="column">Долг</div>
                <!--<div class="column">Просроч. долг</div>-->
                <!--<div class="column">Дней просрочено</div>-->
                <div class="column"></div>
            </div>
        </div>
    </div>
    <?php foreach ($debts as $d): ?>
        <div class="columns is-vcentered order-list">
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column"><?= $d['order_id']; ?></div>
                    <div class="column"><?= $d['1c_number']; ?></div>
                    <div class="column"><?= date("d.m.Y", $d['created_at']); ?></div>
                    <div class="column"><?= date("d.m.Y", $d['shipped_at']); ?></div>
                </div>
            </div>
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column"><?= Yii::$app->formatter->asDecimal($d['debt']); ?></div>
                    <!--<div class="column"><?= Yii::$app->formatter->asDecimal($d['debt_overdue']); ?></div>-->
                    <!--<div class="column"><?= $d['days_overdue']; ?></div>-->
                    <div class="column">
                        <?php if ($d['order_id']): ?>
                            <a class="button is-primary is-outlined" title="Просмотреть"
                               href="<?= Url::to(['order/view', 'order_id' => $d['order_id']]); ?>">
                                <span class="icon"><i class="fas fa-eye"></i></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>