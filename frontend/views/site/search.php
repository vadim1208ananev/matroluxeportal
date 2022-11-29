<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use yii\widgets\LinkPager;

$this->registerJsFile('/js/index.js', ['depends' => [AppAsset::class]]);

$this->title = "Поиск: \"{$q}\"";
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($pages->totalCount > 12): ?>
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
    <?php endif; ?>

    <div class="columns">
        <div class="column">

            <?php foreach ($products as $p): ?>
                <div class="columns is-vcentered product-list">
                    <div class="column">
                        <a href="<?= Url::to(['product/index', 'product_id' => $p->product_id, 's1' => $p->url, 's2' => 'p']) ?>"
                           title="<?= $p->productDesc->name; ?>"
                        <figure class="image">
                            <div class="tag_block">
                                <?php if ($p->productDiscount != null): ?>
                                    <p class="field tag_block__item"><span
                                                class="tag is-danger tag_block__title">Акция</span></p>
                                <?php endif; ?>
                                <?php if ($p->bonus): ?>
                                    <p class="field tag_block__item is-pulled-right"><span
                                                class="tag is-primary tag_block__title"><?= $p->bonus ?></span></p>
                                <?php endif; ?>
                            </div>
                            <img src="/<?= $p->getImage()->getPath('400x'); ?>"
                                 alt="<?= $p->productDesc->name; ?>"
                        </figure>
                        </a>
                    </div>
                    <div class="column has-text-centered-mobile" data-product-id=<?= $p->product_id; ?>>
                        <a href="<?= Url::to(['product/index', 'product_id' => $p->product_id, 's1' => $p->url, 's2' => 'p']) ?>"><?= $p->productDesc->name; ?></a>
                    </div>
                    <div class="column">
                        <div class="columns is-vcentered is-mobile">
                            <div class="column is-two-thirds">
                                <div class="field">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select>
                                                <?php foreach ($sizes[$p->product_id] as $s): ?>
                                                    <option data-size-id=<?= $s['size_id']; ?>><?= $s['name']; ?> <?= $s['price'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <input class="input" type="number" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="columns is-mobile">
                            <div class="column">
                                <button class="button product-list_spec is-primary"
                                        data-product-id="<?= $p->product_id; ?>"
                                        title='Спецификация - это "черновик" заказа'>
                                    В спецификацию
                                </button>
                            </div>
                            <div class="column is-pulled-right">
                                <button class="button product-list_order is-primary"
                                        data-product-id="<?= $p->product_id; ?>">
                                    В заказ
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="is-clearfix"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($pages->totalCount > 12): ?>
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
    <?php endif; ?>
</div>
