<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\ProductAsset;

ProductAsset::register($this);

$this->title = $p->productDesc->name;
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<div class="container content section">
    <div class="columns is-vcentered product-list" data-product-id="<?= $p->product_id; ?>">
        <div class="column is-2-widescreen">
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
        </div>
        <div class="column">
            <div class="columns is-vcentered is-mobile">
                <div class="column">
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
        <div class="column is-pulled-right">
            <div class="columns is-mobile">
                <div class="column">
                    <button class="button product-list_to-spec is-primary"
                            data-product-id="<?= $p->product_id; ?>"
                            title='Спецификация - это "черновик" заказа'>
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

    <?php if ($hasNostandart): ?>
        <h2 class="subtitle">Заказ нестандартного размера</h2>
        <p class="has-text-weight-bold">Пожалуйста, укажите размеры изделия в сантиметрах</p>
        <div class="columns product-list">
            <!--            <p class="help-block help-block-error">Необходимо заполнить «Ширина (см)».</p>-->
            <input class="input" type="hidden" name="productId" value="<?= $p->product_id ?>">
            <input class="input" type="hidden" name="sizeId" value="">
            <input class="input" type="hidden" name="amount" value="1">
            <div class="column">
                <input class="input is-primary" name="width" type="number" placeholder="Ширина (см)">
                <p class="help-block help-block-error"></p>
            </div>
            <div class="column">
                <input class="input is-primary" name="length" type="number" placeholder="Длина (см)">
                <p class="help-block help-block-error"></p>
            </div>
            <div class="column is-pulled-right">
                <button class="button product-list_order_nostandart is-primary no-run"
                        title="В корзину">
                    В корзину
                </button>
            </div>
            <div class="is-clearfix"></div>
        </div>
    <?php endif; ?>

    <div class="columns is-multiline">
        <?php foreach ($p->getImages() as $k => $image): ?>
            <div class="column is-one-third">
                <figure class="image"><img src="/<?= $image->getPath('700x'); ?>"
                                           alt="<?= $p->productDesc->name; ?>"</figure>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>
    <?php foreach ($accs as $title => $acc): ?>
        <h2><?= $title ?></h2>
        <div class="columns is-multiline has-text-centered">
            <?php foreach ($acc as $a): ?>
                <div class="column is-2 product-list">

                    <a href="<?= Url::to(['product/index', 'product_id' => $a->product_id, 's1' => $a->url, 's2' => 'p']) ?>"
                       title="<?= $a->productDesc->name; ?>"
                    <figure class="image">
                        <img src="/<?= $a->getImage()->getPath('200x'); ?>"
                             alt="<?= $a->productDesc->name; ?>"
                    </figure>
                    </a>

                    <a href="<?= Url::to(['product/index', 'product_id' => $a->product_id, 's1' => $a->url, 's2' => 'p']) ?>">
                        <span class="is-block is-clipped acc_tile__title"><?= $a->productDesc->name; ?></span>
                    </a>

                    <div class="field">
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select>
                                    <?php  if(isset($accSizes[$a->product_id])) {?>
                                    <?php foreach ($accSizes[$a->product_id] as $s): ?>
                                        <option data-size-id=<?= $s['size_id']; ?>><?= $s['name']; ?> <?= $s['price'] ?></option>
                                    <?php endforeach; ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="columns is-mobile">
                        <div class="column">
                            <div class="field">
                                <input class="input" type="number" value="1">
                            </div>
                        </div>

                        <div class="column">
                            <button class="button is-primary is-outlined product-list_order"
                                    data-product-id="<?= $a->product_id; ?>" title="В корзину">
                                <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="is-clearfix"></div>
            <?php endforeach; ?>
        </div>
        <hr>
    <?php endforeach; ?>
    <ul>
        <?php foreach ($productAttrs as $pa): ?>
            <li><?= $pa->attrDesc->attr->attrGroup->attrGroupDesc->name; ?> - <?= $pa->attrDesc->name; ?></li>
        <?php endforeach; ?>
    </ul>
</div>