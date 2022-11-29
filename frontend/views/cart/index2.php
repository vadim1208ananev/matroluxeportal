<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

$this->registerJsFile('/js/cart.js', ['depends' => [AppAsset::class]]);

$this->title = 'Корзина';
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php foreach ($products as $p): ?>
        <div class="columns is-vcentered product-list">
            <div class="column is-2-widescreen">
                <a href="<?= Url::to(['product/index', 'product_id' => $p['product']->product_id, 's1' => $p['product']->url, 's2' => 'p']) ?>"
                   title="<?= $p['product']->productDesc->name; ?>"
                <figure class="image"><img src="/<?= $p['product']->getImage()->getPath('200x'); ?>"
                                           alt="<?= $p['product']->productDesc->name; ?>"</figure>
                </a>
            </div>
            <div class="column has-text-centered-mobile">
                <input class="input" type="hidden" name="productId"
                       value="<?= $p['product']->product_id; ?>">
                <a href="<?= Url::to(['product/index', 'product_id' => $p['product']->product_id, 's1' => $p['product']->url, 's2' => 'p']) ?>"><?= $p['product']->productDesc->name; ?></a>
            </div>
            <div class="column">
                <div class="columns is-vcentered is-mobile">
                    <div class="column">
                        <input class="input" type="hidden" name="sizeId"
                               value="<?= $p['sizeId']; ?>"><?= $p['sizeName']; ?></div>
                    <div class="column">
                        <div class="field">
                            <input class="input" type="number" name="amount" value="<?= $p['count']; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-pulled-right">
                <a class="button is-primary is-outlined product-cart_delete" title="Удалить">
                    <span class="icon"><i class="fas fa-trash"></i></span>
                </a>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endforeach; ?>
    <div class="field">
        <div class="control">
            <input class="input is-primary" type="text" name="comment" placeholder="Комментарий">
        </div>
    </div>
    <?php if (Yii::$app->user->isGuest): ?>
        <p>Для заказа, пожалуйста, <a class="has-text-link" title="Войти" href="<?= Url::to(['site/login']) ?>">войдите
                в систему</a> или <a class="has-text-link" title="Зарегистрироваться"
                                     href="<?= Url::to(['site/signup']) ?>">зарегистрируйтесь</a>.</p>
    <?php else: ?>
        <a class="button is-primary product-cart_order" title="Заказать">Заказать</a>
    <?php endif; ?>
</div>
