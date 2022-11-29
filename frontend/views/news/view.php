<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $news->newsDesc->name;
?>
<div class="container content section">
    <div><?= date('d.m.Y', $news->created_at) ?></div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $news->newsDesc->desc; ?>
</div>
