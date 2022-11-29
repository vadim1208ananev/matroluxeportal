<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\models\User;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\widgets\SearchWidget;

AppAsset::register($this);

if (!Yii::$app->user->isGuest) {
    Yii::$app->view->params['isDemo'] = User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username);
}

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
<section class="hero hero__header">
    <div class="hero-head">
        <header class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a href="<?= Url::to(['/']) ?>">
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
                        <!--
                        <a class="navbar-item is-hidden-mobile has-text-weight-bold"
                           href="<?= Url::to(['category/index', 'category_id' => 1, 's1' => 'matrasy', 's2' => 'c']) ?>">Матрасы</a>
                        <a class="navbar-item is-hidden-mobile has-text-weight-bold"
                           onclick="alert('Раздел в разработке!');">Корпусная мебель</a>
                        <a class="navbar-item is-hidden-mobile has-text-weight-bold"
                           onclick="alert('Раздел в разработке!');">Диваны</a>
                           -->
                        <a class="navbar-item is-hidden-mobile has-text-weight-bold"
                           href="<?= Url::to(['category/index', 'category_id' => 1, 's1' => 'matrasy', 's2' => 'c']) ?>">Матрасы</a>
                        <a class="navbar-item is-hidden-mobile has-text-weight-bold"
                           href="<?= Url::to(['category/index', 'category_id' => 2, 's1' => 'aksessuary', 's2' => 'c']) ?>">Аксессуары</a>
                        <?php if (true || in_array(Yii::$app->user->id, [1, 32, 35, 62, 309])): ?>
                            <a class="navbar-item is-hidden-mobile has-text-weight-bold"
                               href="<?= Url::to(['category/index', 'category_id' => 3, 's1' => 'korpusnaya-mebel', 's2' => 'c']) ?>">Корпусная
                                мебель</a>
                        <?php endif; ?>
                        <div class="navbar-item  is-hidden-mobile search_block"><?= SearchWidget::widget(); ?></div>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a class="navbar-item" href="<?= Url::to(['site/cart']) ?>"
                               title="Корзина">Корзина</a>
                            <a class="navbar-item" href="<?= Url::to(['site/login']) ?>"
                               title="Войти">Войти</a>
                            <a class="navbar-item" href="<?= Url::to(['site/signup']) ?>"
                               title="Зарегистрироваться">Зарегистрироваться</a>
                        <?php else: ?>
                            <a class="navbar-item" href="<?= Url::to(['site/cart']) ?>"
                               title="Корзина">Корзина</a>
                            <a class="navbar-item" href="<?= Url::to(['order/index']) ?>"
                               title="Заказы">Заказы</a>
                            <a class="navbar-item" href="<?= Url::to(['site/specs']) ?>"
                               title="Спецификации">Спецификации</a>
                            <a class="navbar-item" href="<?= Url::to(['stock/index']) ?>"
                               title="Заказы">Остатки</a>
                            <a class="navbar-item" href="<?= Url::to(['site/account']) ?>"
                               title="Заказы">Личный кабинет</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
        <div class="hero-body is-hidden-tablet">
            <div class="level">
                <div class="level-item has-text-centered is-hidden-tablet">
                    <div class="field search_block">
                        <?= SearchWidget::widget(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!Yii::$app->user->isGuest && $this->params['isDemo']): ?>
        <p class="has-background-danger has-text-weight-bold is-size-4 has-text-centered">Демо-режим!</p>
    <?php endif; ?>
</section>
<?php if (!Yii::$app->user->isGuest): ?>
    <div class="container">
        <div class="is-size-5 has-text-weight-bold is-pulled-right has-text-primary bonus-layout">
            Мои бонусы: <span
                    class="has-text-danger"><?= Yii::$app->user->getIdentity()->getBonus() ?> грн</span>
        </div>
    </div>
<?php endif; ?>

<?php $this->beginBody() ?>
<?= $content ?>
<div id="modal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content"></div>
    <button class="modal-close is-large" aria-label="close"></button>
</div>
<footer class="hero">
    <div class="hero-head has-background-black-ter">
        <section class="section container has-text-white">
            <div class="columns">
                <div class="column is-one-quarter">
                    <img src="<?= Url::to('@web/images/logo_white.png') ?>" alt="">
                    <br>
                    <br>
                    <p>Мы в соц. сетях</p>
                    <ul class="social-images">
                        <li><a href="https://www.facebook.com/matroluxecompany"><img
                                        src="<?= Url::to('@web/images/facebook.png') ?>" alt=""></a></li>
                        <li><a href="https://www.instagram.com/matroluxe_mebli/"><img
                                        src="<?= Url::to('@web/images/instagram.png') ?>" alt=""></a></li>
                        <li><a href="https://www.youtube.com/channel/UC-qC7_KG_OMJpljq_cYvklQ/videos"><img
                                        src="<?= Url::to('@web/images/youtube.png') ?>" alt=""></a></li>
                    </ul>
                    <br>
                    <p>Фирменный магазин: г.Днепр, ул Николая Руденка, 53</p>
                    <p>Бесплатная горочая линия: +380 8003 005 46</p>
                    <p>Сервис поддержки клиентов: +380 8003 000 47</p>
                    <p><a href="mailto:matroluxe.ua@gmail.com?subject=Вопрос по В2В-порталу">matroluxe.ua@gmail.com</a>
                    </p>
                </div>
                <div class="column">
                    <p class="subtitle">Информация</p>
                    <ul>
                        <li><a href="<?= Url::to(['site/about-us']) ?>">О нас</a></li>
                        <li><a href="<?= Url::to(['site/dostavka-i-oplata']) ?>">Доставка и оплата</a></li>
                        <li><a href="<?= Url::to(['site/usloviya-vozvrata']) ?>">Условия возврата</a></li>
                        <li><a href="<?= Url::to(['site/dogovor-oferty']) ?>">Договор оферты</a></li>
                    </ul>
                </div>
                <div class="column">
                    <p class="subtitle">О компании</p>
                    <p>Компания MATROLUXE специализируется на производстве ортопедических матрасов, мягкой и корпусной
                        мебели превосходного качества.</p>
                    <br>
                    <p>Сотрудники компании MATROLUXE – эксперты в области продаж ортопедических матрасов и другой
                        мебели. Цель нашего предприятия – помочь нашим клиентам в выборе лучшего матраса и обеспечить
                        максимальный комфорт обслуживания.</p>
                </div>
                <div class="column">
                    <img src="<?= Url::to('@web/images/visamastercard.png') ?> " style="height: 2.5em;"
                         alt="Visa, MasterCard">
                </div>
            </div>
        </section>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
