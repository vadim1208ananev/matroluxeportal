<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\widgets\SearchWidget;
use yii\widgets\ActiveForm;

AppAsset::register($this);
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
<section class="hero is-primary">
    <div class="hero-head">
        <header class="navbar">
            <div class="container">
                <div class="navbar-brand has-background-grey-lighter">
                    <a class="navbar-item" href="<?= Url::to(['/']) ?>">
                        <img src="<?= Url::to('@web/images/logo.png') ?>" alt="">
                    </a>
                    <span class="navbar-burger burger" data-target="navbarMenuHeroC">
            <span></span>
            <span></span>
            <span></span>
          </span>
                </div>
                <div id="navbarMenuHeroC" class="navbar-menu">
                    <div class="navbar-end">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a class="navbar-item" href="<?= Url::to(['site/login']) ?>"
                               title="Войти">Войти</a>
                            <a class="navbar-item" href="<?= Url::to(['site/signup']) ?>"
                               title="Зарегистрироваться">Зарегистрироваться</a>
                            <a class="navbar-item" href="<?= Url::to(['site/cart']) ?>"
                               title="Зарегистрироваться">Корзина</a>
                        <?php else: ?>
                            <a class="navbar-item" href="<?= Url::to(['site/cart']) ?>"
                               title="Зарегистрироваться">Корзина</a>
                            <a class="navbar-item" href="<?= Url::to(['specification/index']) ?>"
                               title="Зарегистрироваться">Спецификации</a>
                            <a class="navbar-item" href="<?= Url::to(['order/index']) ?>"
                               title="Зарегистрироваться">Заказы</a>
                            <?php echo Html::a('Выход', ['site/logout'], ['class' => 'navbar-item', 'data' => ['method' => 'post']]); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
        <div class="hero-body">
            <div class="level">
                <div class="level-item has-text-centered">
                    <div class="field is-grouped search_block">
                        <?= SearchWidget::widget(); ?>
                        <p class="control">
                            <input class="input" type="text" placeholder="Найти">
                        </p>
                        <p class="control">
                            <a class="button is-info">
                                <span class="icon has-text-white"><i class="fas fa-search"></i></span>
                            </a>
                        </p>
                        <div class="search_elems">
                            <a class="panel-block">
                                marksheet
                            </a>
                            <a class="panel-block">
                                marksheet222
                            </a>
                            <a class="panel-block">
                                marksheet444
                            </a>
                            <a class="panel-block">
                                marksheet666
                            </a>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="field is-grouped">
                        <p class="control">
                            <button class="button is-primary is-inverted"><a>Корпусная мебель</a></button>
                        </p>
                        <p class="control">
                            <button class="button is-primary is-inverted"><a>Диваны</a></button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->beginBody() ?>
<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
