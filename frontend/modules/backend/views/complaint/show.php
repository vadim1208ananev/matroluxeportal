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
        'label' => 'Фото рекламаций',
    ],
];

?>
<div class="section container content">
    <h1>Фото рекламаций</h1>
    <?php if (empty($images)) { ?>
        Фото нет
    <?php } else { ?>
        <?php $i=0; foreach ($images as $key=>$image) { ?>
            <?php echo ++$i;   ?>
            <div>
                <img class="fit-picture" src="<?php echo $image;   ?>" alt="Foto">
           
            </div>
            <hr>
            <?php }  ?>
        <?php }  ?>


</div>