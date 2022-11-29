<?php

use yii\helpers\Html;
use frontend\assets\BackendAppAsset;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

BackendAppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" data-auto-replace-svg="nest"></script>
    <?php $this->head() ?>
</head>
<body>
<section class="hero">
    <div class="hero-head">
        <header class="navbar">
            <div class="container">
                <div id="navbarMenuHeroC" class="navbar-menu">
                    <div class="navbar-end">
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?= Html::a('Выход', ['default/logout'], [
                                'class' => 'navbar-item',
                                'title' => 'Выход',
                                'data' => ['method' => 'post']]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
    </div>
</section>
<?php $this->beginBody() ?>

<?php if (!empty($this->params['breadcrumbs'])): ?>
    <div class="container content">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <?php
            echo Breadcrumbs::widget([
                'homeLink' => [
                    'label' => 'Главная',
                    'url' => Url::to(['default/index']),
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'activeItemTemplate' => "<li class=\"is-active\">{link}</li>\n",
            ]);
            ?>
        </nav>
    </div>
<?php endif; ?>
<?= $content ?>
<footer>

</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
