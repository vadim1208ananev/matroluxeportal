<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Рекламации',
    ],
];

?>
<div class="section container content">
    <h1>Рекламации</h1>
    <div class="columns is-vcentered item-list">
        <div class="column">ИД</div>
        <div class="column">ФИО</div>
        <div class="column">Телефон</div>
        <div class="column">Доставка</div>
        <div class="column">Название товара матраса</div>
        <div class="column">Характеристики матраса</div>
        <div class="column">Название товара корпусная мебель</div>
        <div class="column">Характеристики корпусной мебели</div>

        <div class="column">Месяц покупки</div>
        <div class="column">Год покупки</div>
        <div class="column">Тип рекламации</div>
        <div class="column">Фото</div>
        <!-- <div class="column">Бонус</div>-->
        <div class="column">Отправка в 1с</div>
    </div>
    <?php foreach ($complaints as $complaint) : ?>
        <div class="columns is-vcentered item-list">
            <div class="column"><?= $complaint->complaint_id ?></div>
            <div class="column"><?= $complaint->fio ?></div>
            <div class="column"><?= $complaint->tel ?></div>
            <div class="column"><?= $complaint->description ?></div>
            <div class="column"><?= $complaint->product->name ?></div>

            <div class="column"><?= $complaint->size->sizeDesc->name ?></div>

            <div class="column"><?= $complaint->productcm->name ?></div>
            <div class="column"><?= $complaint->attrs ?></div>

            <div class="column"><?= $complaint->purchase_month ?></div>
            <div class="column "><?= $complaint->purchase_year ?></div>
            <div class="column "><?= $complaint->type ?></div>
            <div class="column "> <button class="button is-primary">
                    <?= Html::a('Фото', ['complaint/show', 'complaint_id' => $complaint->complaint_id], ['class' => 'has-text-white']) ?>
                </button></div>



            <div class="column">
                <?php  if(!$complaint->is_send) { ?>
                <button class="button is-primary">

                    <?= Html::a('Отправка', null, ['class' => 'has-text-white sendto1c', 'data-id' => $complaint->complaint_id]) ?>
                </button>
                <?php  } else { ?>
                    <button class="button is-primary">

                    <?= Html::a('Отправлено', null, ['class' => 'has-text-white']) ?>
                </button>

                    <?php  }  ?>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
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
</div>