<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class CategoryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/category.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\AppAsset',
    ];
}
