<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Рекламации',
        'url' => Url::to('/backend/complaint/index'),
    ],
    [
        'label' => 'Детальная информация',
    ],
];

?>



<div class="section container content">
    <h1>Детальная информация</h1>
    <div class="columns">
        <div class="column">
            <div class="box">
                <p class="subtitle has-text-weight-bold">ID</p>
                <div><?php echo $complaint->complaint_id ?></div>
            </div>

            <div class="box">
                <p class="subtitle has-text-weight-bold">Фио</p>
                <div><?php echo $complaint->fio ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Телефон</p>
                <div><?php echo $complaint->tel ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Доставка</p>
                <div><?php echo $complaint->description ?></div>
            </div>

            <div class="box">
                <p class="subtitle has-text-weight-bold">Название товара матраса</p>
                <div><?php echo $complaint->product->name ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Характеристики матраса</p>
                <div><?php echo $complaint->size->sizeDesc->name ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Название товара корпусная мебель</p>
                <div><?php echo $complaint->productcm->name ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Месяц покупки</p>
                <div><?php echo $complaint->purchase_month ?></div>
            </div>

            <div class="box">
                <p class="subtitle has-text-weight-bold">Год покупки</p>
                <div><?php echo $complaint->purchase_year ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Тип рекламации</p>
                <div><?php echo $complaint->type ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Коментарий</p>
                <div><?php echo $complaint->comment ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">Отправлять в 1с?</p>
                <div><?php echo $complaint->is_send ? 'Да' : 'Нет' ?></div>
            </div>
            <div class="box">
                <p class="subtitle has-text-weight-bold">1c_id</p>
                <div><?php echo $complaint['1c_id'] ?></div>
            </div>
            <div class="box1">
                <?php if (empty($images)) { ?>
                    Фото нет
                <?php } else { ?>
                    <?php $i = 0;
                    foreach ($images as $key => $image) { ?>
                        <?php echo ++$i;   ?>
                        <div>
                            <img class="fit-picture" src="<?php echo $image;   ?>" width="600" height="500" alt="Foto">

                        </div>
                        <hr>
                    <?php }  ?>
                <?php }  ?>


            </div>


        </div>

    </div>







</div>
<style>
    .box {
        display: flex;
        gap: 15px;
    }
    .box div{
        font-size: 20px;
    }
</style>