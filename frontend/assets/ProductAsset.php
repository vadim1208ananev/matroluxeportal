<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ProductAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/main-new.js',
        'js/product.js',
        'js/nested-set.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\AppAsset',
    ];
}
