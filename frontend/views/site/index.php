<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use yii\widgets\LinkPager;

$this->registerJsFile('/js/index.js', ['depends' => [AppAsset::class]]);

$this->title = '';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="columns">
        <div class="column is-one-quarter">
            <div class="box">
                <h2 class="subtitle">Каталоги</h2>
                <?php if (false): ?>
                    <div><a class="has-text-link" href="<?= Url::to('@web/pdf/Luxe-Studio1.pdf') ?>" target="_blank">Прайсы
                            Luxe-Studio.pdf</a>
                    </div>
                <?php endif; ?>
                <div><a class="has-text-link" href="<?= Url::to('@web/pdf/Luxe-Studio2.pdf') ?>" target="_blank">Шкафы-купе
                        Luxe-Studio.pdf</a>
                </div>
                <?php if (false): ?>
                    <div><a class="has-text-link" href="<?= Url::to('@web/pdf/Luxe-Studio3.pdf') ?>" target="_blank">Каталог
                            корпусной мебели Luxe-Studio.pdf</a>
                    </div>
                <?php endif; ?>
                <div><a class="has-text-link" href="<?= Url::to('@web/pdf/Luxe-Studio4.pdf') ?>" target="_blank">Корпусная
                        мебель Luxe-Studio.pdf</a>
                </div>
            </div>
            <div class="box">
                <h2 class="subtitle">Новости</h2>

                <?php if (false): ?>
                    <figure class="image figure-trim"><img
                                src="<?= Url::to('@web/images/promotion1802_2102.jpg') ?>" alt="">
                    </figure>
                    <div>18.02.2022</div>
                    <div class="has-text-weight-bold">Покупай и зарабатывай</div>
                    <div>Дополнительный бонус 5%</div>
                    <hr>

                    <figure class="image figure-trim"><img
                                src="<?= Url::to('@web/images/kiss.jpg') ?>" alt="">
                    </figure>
                    <div>10.02.2022</div>
                    <div class="has-text-weight-bold">Друзі, для вас чудова новина! З 10.02.2022 по 21.02.2022 ми
                        запускаємо
                        акційну пропозицію, окрім знижок до 50 % на
                        улюблені матраци до кожного двоспального матраца у ПОДАРУНОК КОМПЛЕКТ ПОСТІЛЬНОЇ БІЛИЗНИ!
                    </div>
                    <div>До розмірів 140х190/200, 150х190/200, 160х190/200, а також нестандартних розмірів шириною від
                        141 до
                        160 - у
                        подарунок двоспальний розмір постільної білизни.
                    </div>
                    <div>До розмірів 180х190/200, а також нестандартних розмірів шириною від 161 та більше - у подарунок
                        комплект
                        постільної білизни євро.
                    </div>
                    <div>Постільна білизна - 100 % бавовна.</div>
                    <div>Окрім чудових знижок та подарунків, діє програма кешбеку! Не втратьте шанс, адже пропозиція
                        обмежена.
                    </div>
                    <hr>
                <?php endif; ?>

                <figure class="image figure-trim"><img
                            src="<?= Url::to('@web/images/wardrobe-first-order.jpg') ?>" alt="">
                </figure>
                <div>03.02.2022</div>
                <div class="has-text-weight-bold">5% на первый заказ</div>
                <div>Заказы корпусной мебели</div>
                <hr>

                <figure class="image figure-trim"><img
                            src="<?= Url::to('@web/images/meest-express.jpg') ?>" alt="">
                </figure>
                <div>28.12.2021</div>
                <div class="has-text-weight-bold">Meest Express</div>
                <div>Добавлена возможность выбора службы доставки Meest Express</div>
                <hr>

                <?php if (false): ?>
                    <figure class="image figure-trim"><img
                                src="<?= Url::to('@web/images/bum-3-bum-4-so-skidkoy-20.jpg') ?>" alt="">
                    </figure>
                    <div>16.12.2021</div>
                    <div class="has-text-weight-bold">Бум-3 и Бум-4 со скидкой 20%</div>
                    <div>В подарок для каждого размера матраса - одеяло</div>
                    <a href="<?= Url::to('news/n6') ?>"><span class="has-text-link">Подробнее..</span></a>
                    <hr>
                <?php endif; ?>

                </figure>
                <div>19.10.2021</div>
                <div class="has-text-weight-bold"><span class="has-text-danger">Новинка! </span>Заказы шкафов-купе
                    Классик и Стандарт
                </div>
                <div>Заказывайте шкафы-купе Классик/Стандарт через портал</div>
                <a href="<?= Url::to('news/n5') ?>"><span class="has-text-link">Подробнее..</span></a>
                <hr>

                <figure class="image figure-trim"><img
                            src="<?= Url::to('@web/images/korpusnaya-mebel.jpg') ?>" alt="">
                </figure>
                <div>01.08.2021</div>
                <div class="has-text-weight-bold">Заказы корпусной мебели (профиль Бавария)</div>
                <div>Добавлена возможность заказов корпусной мебели с профилем Бавария</div>
                <a href="<?= Url::to('news/n4') ?>"><span class="has-text-link">Подробнее..</span></a>
                <hr>

                <figure class="image figure-trim"><img
                            src="<?= Url::to('@web/images/register.jpg') ?>" alt="">
                </figure>
                <div>21.04.2021</div>
                <div class="has-text-weight-bold">Начисляем 5% бонусов от суммы первого заказа</div>
                <div>После регистрации в личном кабинете и оформления первого заказа получайте 5% бонусов</div>
                <a href="<?= Url::to('news/n3') ?>"><span class="has-text-link">Подробнее..</span></a>
                <hr>

                <figure class="image figure-trim"><img
                            src="<?= Url::to('@web/images/nostandart.jpg') ?>" alt="">
                </figure>
                <div>13.04.2021</div>
                <div class="has-text-weight-bold">Заказы изделий нестандартных размеров</div>
                <div>Теперь возможен заказ матраса или наматрасника нестандартных размеров</div>
                <a href="<?= Url::to('news/n2') ?>"><span class="has-text-link">Подробнее..</span></a>
                <hr>

                <figure class="image figure-trim"><img
                            src="<?= Url::to('@web/images/bonus.jpg') ?>" alt="">
                </figure>
                <div>23.03.2021</div>
                <div class="has-text-weight-bold">Теперь накапливайте бонусы за покупки!</div>
                <div>Бонус начисляется на заказ со статусом "Отгружен", оформленный через портал. В дальнейшем можно
                    будет расплачиваться бонусами в размере 70% от суммы заказа!
                </div>
                <a href="<?= Url::to('news/n1') ?>"><span class="has-text-link">Подробнее..</span></a>

            </div>
        </div>
        <div class="column main_banner has-text-white is-hidden-mobile">
            <p class="main_banner__hello is-size-2 has-text-weight-bold"><?= Yii::$app->user->isGuest ? 'Здравствуйте!' : 'Здравствуйте, ' . Yii::$app->user->getIdentity()->username . '!' ?></p>
            <p class="main_banner__desc is-size-6">Компания Матролюкс рада приветствовать вас в онлайн-магазине оптовых
                закупок</p>
            <p class="main_banner__category has-text-weight-bold is-size-5">Выберите категорию</p>
            <div class="main_banner__buttons">
                <div class="main_banner__button">
                    <figure class="image active">
                        <a href="<?= Url::to(['category/index', 'category_id' => 1, 's1' => 'matrasy', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/matr.png') ?>" alt=""></a>
                    </figure>
                    <figure class="image inactive">
                        <a href="<?= Url::to(['category/index', 'category_id' => 1, 's1' => 'matrasy', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/matr_active.png') ?>"
                                    alt=""></a>
                    </figure>
                </div>
                <div class="main_banner__button">
                    <figure class="image active">
                        <a href="<?= Url::to('/') ?>"><img src="<?= Url::to('@web/images/corp.png') ?>" alt=""></a>
                    </figure>
                    <figure class="image inactive">
                        <a href="<?= Url::to(['category/index', 'category_id' => 3, 's1' => 'korpusnaya-mebel', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/corp_active.png') ?>"
                                    alt=""></a>
                    </figure>
                </div>
                <div class="main_banner__button">
                    <figure class="image active">
                        <a href="<?= Url::to(['category/index', 'category_id' => 2, 's1' => 'aksessuary', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/access.png') ?>" alt=""></a>
                    </figure>
                    <figure class="image inactive">
                        <a href="<?= Url::to(['category/index', 'category_id' => 2, 's1' => 'aksessuary', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/access_active.png') ?>" alt=""></a>
                    </figure>
                </div>
            </div>
        </div>
        <div class="column is-hidden-tablet">
            <p class="is-size-4 has-text-weight-bold"><?= Yii::$app->user->isGuest ? 'Здравствуйте!' : 'Здравствуйте, ' . Yii::$app->user->getIdentity()->username . '!' ?></p>
            <p class="is-size-6">Компания Матролюкс рада приветствовать вас в онлайн-магазине оптовых
                закупок</p>
            <p class="has-text-weight-bold is-size-6">Выберите категорию</p>
            <div class="columns">
                <div class="column">
                    <figure class="image active">
                        <a href="<?= Url::to(['category/index', 'category_id' => 1, 's1' => 'matrasy', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/matr.png') ?>" alt=""></a>
                    </figure>
                </div>
                <div class="column">
                    <figure class="image active">
                        <a href="<?= Url::to(['category/index', 'category_id' => 3, 's1' => 'korpusnaya-mebel', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/corp.png') ?>" alt=""></a>
                    </figure>
                </div>
                <div class="column">
                    <figure class="image active">
                        <a href="<?= Url::to(['category/index', 'category_id' => 2, 's1' => 'aksessuary', 's2' => 'c']) ?>"><img
                                    src="<?= Url::to('@web/images/access.png') ?>" alt=""></a>
                    </figure>
                </div>
            </div>
        </div>
    </div>
