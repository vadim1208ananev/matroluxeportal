<?php
return [
    'language' => 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            //be sure, that permissions ok
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => '@frontend/web/images/store', //path to origin images
            'imagesCachePath' => 'images/cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            'placeHolderPath' => '@frontend/web/images/placeHolder.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
        ],
        'backend' => [
            'class' => 'app\modules\backend\Module',
            'layout' => 'main',
        ],
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
//            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',

//                'controller:[\w-]+>' => '<controller>/index', //orders - orders/index
                'orders' => 'order/index',
                'orders/<order_id:\d+>' => 'order/view', //orders/1 - orders?order_id=1
                'orders/<action:[\w-]+>' => 'order/<action>',
                'specs' => 'spec/index',
                'specs/<spec_id:\d+>' => 'spec/view',
                'specs/delete/<spec_id:\d+>' => 'spec/delete',
                'specs/delete-current/<spec_id:\d+>' => 'spec/delete-current',
                'specs/update/<spec_id:\d+>' => 'spec/update',
                'specs/order/<spec_id:\d+>' => 'spec/order',
                'cart' => 'cart/index',
                'debts' => 'debt/index',
                'stocks' => 'stock/index',
//                'news' => 'news/index',

//                'logout' => 'site/logout',
//                '<s1:[\w-]+>/<s2:[c]><category_id:\d+>/<s3:[\w\-\=\;\,]+>' => 'category/index',
                '<s1:[\w-]+>/<s2:[p]><product_id:\d+>' => 'product/index',
                '<s1:[\w-]+>/<s2:[c]><category_id:\d+>' => 'category/index',
                '<s1:[\w-]+>/<s2:[n]><news_id:\d+>' => 'news/view',

                'token/<token:[\w\d\-_]+>' => 'site/token',

                //module backend
                'backend' => 'backend/default/index',
                'backend/login' => 'backend/default/login',
                'backend/users' => 'backend/user/index',
                'backend/orders' => 'backend/order/index',
                'backend/bonuses' => 'backend/bonus/index',
                'backend/maps' => 'backend/map/index',
                'backend/users/<user_id:\d+>/orders' => 'backend/user/order',
                'backend/users/<user_id:\d+>/bonuses' => 'backend/user/bonus',

                'backend/products' => 'backend/product/index',
                'backend/<controller:\w+>/<action:\w+>' => 'backend/<controller>/<action>',
                '<action:[\w-]+>' => 'site/<action>',

                'backend/services/import-wardrobe-door' => 'backend/service/import-wardrobe-door',

//                'users/<user_id:\d+>/posts' => 'user-posts/index',
//                'users/<user_id:\d+>/posts/<id:\d+>' => 'user-posts/view',
//                'users/<user_id:\d+>/posts/<id:\d+>/<_a:[\w-]+>' => 'user-posts/<_a>',
//                'users/<user_id:\d+>/posts/<_a:[\w-]+>' => 'user-posts/<_a>',
//
//                '<_c:[\w-]+>' => '<_c>/index',
//                '<_c:[\w-]+>/<id:\d+>' => '<_c>/view',
//                '<_c:[\w-]+>/<id:\d+>/<_a:[\w-]+>' => '<_c>/<_a>',

//                '<action:(account|compare|favorites|login|logout|new-customer|forgot-password|change-password|about-us|privacy|terms|search|study|dostavka-i-oplata|garantiya-i-vozvrat|kontrol-kachestva|pravila-ekspluatacii-matrasa|start)>' => 'site/<action>',
//                'selection' => 'category/selection',
////                'category/<action:\w+>' => 'category/<action>',
//                'wishlist' => 'wishlist/index',
//                'wishlist/<action:\w+>' => 'wishlist/<action>',
//                'cart' => 'cart/index',
//                'cart/<action:\w+>' => 'cart/<action>',
//                'blog' => 'blog/index',
////                'blog/<action:\w+>' => 'blog/<action>',
////                'yii2admin/<action:\w+>' => 'yii2admin/<action>',
//                'yii2admin' => 'yii2admin/default/index',
//                'yii2admin/attribute-group' => 'yii2admin/attribute-group/index',
//                'yii2admin/<controller:\w+>/<action:\w+>' => 'yii2admin/<controller>/<action>',
////                'wishlist/<id:\d+>' => 'wishlist/add',
////                '<action:\w+>' => 'site/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@common/rbac/items.php',
            'assignmentFile' => '@common/rbac/assignments.php',
            'ruleFile' => '@common/rbac/rules.php',
            'defaultRoles' => ['author'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['debug'],
                    'logFile' => '@runtime/logs/debug.log',
                    'logVars' => [],
                ],
            ],
        ],
    ],
];
