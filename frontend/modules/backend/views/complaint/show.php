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
        <?php foreach ($images as $image) { ?>
            <div>
                <img class="fit-picture" src="<?php echo $image;   ?>" alt="Grapefruit slice atop a pile of other slices">
            <?php }  ?>
            </div>
        <?php }  ?>


</div>