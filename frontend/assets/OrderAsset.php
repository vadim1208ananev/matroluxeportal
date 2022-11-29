<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class OrderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $css = [
//        'css/bulmastyles.css',
//        'css/styles.css',
//    ];
    public $js = [
        'js/order.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\AppAsset',
    ];
}
