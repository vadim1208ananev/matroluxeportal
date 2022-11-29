<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use yii\widgets\LinkPager;
use frontend\assets\CategoryAsset;

$this->registerJsFile('/js/index.js', ['depends' => [AppAsset::class]]);
CategoryAsset::register($this);

$this->title = $title;
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="columns">
        <div class="column is-one-quarter">
            <aside class="menu">
                <div class="box">
                    <p class="buttons is-pulled-right">
                        <a class="button is-primary is-outlined is-small menu_filter__save"
                           title="Сохранить фильтр">
                            <span class="icon is-small"><i class="fas fa-save"></i></span>
                        </a>
                        <a class="button is-primary is-outlined is-small menu_filter__restore"
                           title="Восстановить фильтр">
                            <span class="icon is-small"><i class="fas fa-window-restore"></i></span>
                        </a>
                        <a class="button is-primary is-outlined is-small" href="<?= $indexPage ?>"
                           title="Сбросить фильтр">
                            <span class="icon is-small"><i class="fas fa-times"></i></span>
                        </a>
                    </p>
                    <div class="is-clearfix"></div>
                    <?php foreach ($attrs as $attr): ?>
                        <div class="menu_category is-active" data-ag-name="<?= $attr['attrGroupDesc']['name'] ?>"
                             data-ag-id="<?= $attr['attrGroupDesc']['attr_group_id'] ?>"
                             data-url="<?= $attr['url'] ?>">
                            <header class="menu_label">
                                <a class="menu_label__toggle">
                                    <span class="icon"><i class="fas fa-chevron-up"></i></span>
                                </a>
                                <span class="menu-label has-text-weight-bold"><?= $attr['attrGroupDesc']['name'] ?></span>
                            </header>
                            <ul class="menu-list">
                                <div class="buttons">
                                    <?php foreach ($attr['attrs'] as $a): ?>
                                        <li data-a-name="<?= $a['name']; ?>" data-a-id="<?= $a['attr_id']; ?>">
                                            <a class="button <?= $a['css'] ?> menu_category__button is-small<?= $a['selected'] ? '' : ' is-outlined' ?>"
                                               href="<?= $a['url'] ?>"><?= $a['name']; ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </div>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>

        </div>
        <div class="column">
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

            <?php foreach ($products as $p): ?>
                <div class="columns is-vcentered product-list" data-product-id=<?= $p->product_id; ?>>
                    <div class="column is-2-widescreen">
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
                            <img src="/<?= $p->getImage()->getPath('200x'); ?>"
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
                                                <?php if (isset($sizes[$p->product_id])): ?>
                                                    <?php foreach ($sizes[$p->product_id] as $s): ?>
                                                        <option data-size-id=<?= $s['size_id']; ?>><?= $s['name'] ?> <?= $s['price'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
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
                                <button class="button product-list_to-spec is-primary"
                                        data-product-id="<?= $p->product_id; ?>"
                                        title="Спецификация - это 'черновик' заказа">
                                    В спецификацию
                                </button>
                            </div>
                            <div class="column is-pulled-right">
                                <button class="button product-list_order is-primary"
                                        data-product-id="<?= $p->product_id; ?>" title="В корзину">
                                    В корзину
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="is-clearfix"></div>
                </div>
            <?php endforeach; ?>
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
    </div>
</div>
