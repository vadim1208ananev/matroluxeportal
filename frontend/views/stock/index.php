<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = isset($warehouse) ? 'Остатки' . ' (' . $warehouse->name . ')' : 'Остатки';
?>
<div class="container content section">
    <span class="has-text-danger has-text-weight-bold is-size-5">На данный момент заказ из остатков по региональным складам не резервируется!</span>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (isset($stocks)): ?>
        <div class="columns has-text-weight-bold is-mobile">
            <div class="column">Номенклатура</div>
            <div class="column">Размер</div>
            <div class="column">Остаток</div>
            <div class="column"></div>
        </div>

        <?php if (!$isDemo): ?>
            <?php foreach ($stocks as $s): ?>
                <div class="columns is-mobile">
                    <div class="column has-text-weight-bold">
                        <?= $s['productName']; ?>
                        <?php if ($s['productDiscount'] != null): ?>
                            <span class="tag is-danger tag_block__title">Акция</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php foreach ($s['sizes'] as $size): ?>
                    <div class="columns stock-list product-list">
                        <div class="column is-hidden-mobile">
                            <input class="input" type="hidden" name="productId" value="<?= $s['productId']; ?>">
                            <?= $s['productName']; ?>
                        </div>
                        <div class="column">
                            <div class="columns is-vcentered is-mobile">
                                <div class="column">
                                    <input class="input" type="hidden" name="sizeId" value="<?= $size['sizeId']; ?>">
                                    <?= $size['sizeName']; ?>
                                </div>
                                <div class="column">
                                    <div class="field">
                                        <input class="input" type="number" name="amount" value="<?= $size['stock']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="columns is-mobile">
                                <div class="column">
                                    <button class="button product-list_to-spec is-primary"
                                            data-product-id="<?= $s['productId']; ?>"
                                            title="Спецификация - это 'черновик' заказа">
                                        В спецификацию
                                    </button>
                                </div>
                                <div class="column is-pulled-right">
                                    <button class="button product-list_order is-primary"
                                            data-product-id="<?= $s['productId']; ?>" title="В корзину">
                                        В корзину
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="is-clearfix"></div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="columns is-mobile">
                <div class="column has-text-weight-bold">Матрас Flip Granat cocos/Гранат кокос</div>
            </div>
            <div class="columns stock-list product-list">
                <div class="column is-hidden-mobile">
                    <input class="input" type="hidden" name="productId" value="1">
                    Матрас Flip Granat cocos/Гранат кокос
                </div>
                <div class="column">
                    <div class="columns is-vcentered is-mobile">
                        <div class="column">
                            <input class="input" type="hidden" name="sizeId" value="1">
                            70x190
                        </div>
                        <div class="column">
                            <div class="field">
                                <input class="input" type="number" name="amount" value="2">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns is-mobile">
                        <div class="column">
                            <button class="button product-list_to-spec is-primary"
                                    data-product-id="1"
                                    title="Спецификация - это 'черновик' заказа">
                                В спецификацию
                            </button>
                        </div>
                        <div class="column is-pulled-right">
                            <button class="button product-list_order is-primary"
                                    data-product-id="1" title="В корзину">
                                В корзину
                            </button>
                        </div>
                    </div>
                </div>
                <div class="is-clearfix"></div>
            </div>
            <div class="columns stock-list product-list">
                <div class="column is-hidden-mobile">
                    <input class="input" type="hidden" name="productId" value="1">
                    Матрас Flip Granat cocos/Гранат кокос
                </div>
                <div class="column">
                    <div class="columns is-vcentered is-mobile">
                        <div class="column">
                            <input class="input" type="hidden" name="sizeId" value="2">
                            80x190
                        </div>
                        <div class="column">
                            <div class="field">
                                <input class="input" type="number" name="amount" value="2">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns is-mobile">
                        <div class="column">
                            <button class="button product-list_to-spec is-primary"
                                    data-product-id="1"
                                    title="Спецификация - это 'черновик' заказа">
                                В спецификацию
                            </button>
                        </div>
                        <div class="column is-pulled-right">
                            <button class="button product-list_order is-primary"
                                    data-product-id="1" title="В корзину">
                                В корзину
                            </button>
                        </div>
                    </div>
                </div>
                <div class="is-clearfix"></div>
            </div>
            <div class="columns stock-list product-list">
                <div class="column is-hidden-mobile">
                    <input class="input" type="hidden" name="productId" value="2">
                    Матрас Flip Breeze/Бриз
                </div>
                <div class="column">
                    <div class="columns is-vcentered is-mobile">
                        <div class="column">
                            <input class="input" type="hidden" name="sizeId" value="1">
                            70x190
                        </div>
                        <div class="column">
                            <div class="field">
                                <input class="input" type="number" name="amount" value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns is-mobile">
                        <div class="column">
                            <button class="button product-list_to-spec is-primary"
                                    data-product-id="2"
                                    title="Спецификация - это 'черновик' заказа">
                                В спецификацию
                            </button>
                        </div>
                        <div class="column is-pulled-right">
                            <button class="button product-list_order is-primary"
                                    data-product-id="2" title="В корзину">
                                В корзину
                            </button>
                        </div>
                    </div>
                </div>
                <div class="is-clearfix"></div>
            </div>
            <div class="columns stock-list product-list">
                <div class="column is-hidden-mobile">
                    <input class="input" type="hidden" name="productId" value="2">
                    Матрас Flip Breeze/Бриз
                </div>
                <div class="column">
                    <div class="columns is-vcentered is-mobile">
                        <div class="column">
                            <input class="input" type="hidden" name="sizeId" value="2">
                            80x190
                        </div>
                        <div class="column">
                            <div class="field">
                                <input class="input" type="number" name="amount" value="2">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns is-mobile">
                        <div class="column">
                            <button class="button product-list_to-spec is-primary"
                                    data-product-id="2"
                                    title="Спецификация - это 'черновик' заказа">
                                В спецификацию
                            </button>
                        </div>
                        <div class="column is-pulled-right">
                            <button class="button product-list_order is-primary"
                                    data-product-id="2" title="В корзину">
                                В корзину
                            </button>
                        </div>
                    </div>
                </div>
                <div class="is-clearfix"></div>
            </div>
            <div class="columns stock-list product-list">
                <div class="column is-hidden-mobile">
                    <input class="input" type="hidden" name="productId" value="2">
                    Матрас Flip Breeze/Бриз
                </div>
                <div class="column">
                    <div class="columns is-vcentered is-mobile">
                        <div class="column">
                            <input class="input" type="hidden" name="sizeId" value="3">
                            90x190
                        </div>
                        <div class="column">
                            <div class="field">
                                <input class="input" type="number" name="amount" value="3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns is-mobile">
                        <div class="column">
                            <button class="button product-list_to-spec is-primary"
                                    data-product-id="2"
                                    title="Спецификация - это 'черновик' заказа">
                                В спецификацию
                            </button>
                        </div>
                        <div class="column is-pulled-right">
                            <button class="button product-list_order is-primary"
                                    data-product-id="2" title="В корзину">
                                В корзину
                            </button>
                        </div>
                    </div>
                </div>
                <div class="is-clearfix"></div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>Для отображения остатков по региональному складу
            перейдите <?= Html::a('Личный кабинет / Выбор региона', ['site/change-warehouse'], [
                'class' => 'has-text-link',
            ]) ?></p>
    <?php endif; ?>
</div>
