<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'language' => 'ru',
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
  'bootstrap' => ['log',
        'on beforeRequest' => function () {
       /*     if (!Yii::$app->request->isSecureConnection) {
                $url = Yii::$app->request->getAbsoluteUrl();
                $url = str_replace('http:', 'https:', $url);
                Yii::$app->getResponse()->redirect($url);
                Yii::$app->end();
            }*/
        },
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
        ]
    ],
    'components' => [
        'request' => [
            'baseUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'application/xml' => 'yii\web\XmlParser',
            ],
        ],
        'response' => [
            'format' => 'json',
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'scriptUrl' => 'api/web/index.php',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'auth' => 'site/login',

                
//                'GET profile' => 'profile/index',
//                'PUT,PATCH profile' => 'profile/update',
//                'PUT,PATCH order' => 'order/update',
//                ['class' => 'yii\rest\UrlRule', 'controller' => 'post'],
                'orders/<id:[\d\w\-]+>' => 'order/update',
                
                'complaints/<id:[\d\w\-]+>' => 'complaint/update',

//                '<_c:[\w-]+>' => '<_c>/index',
//                '<_c:[\w-]+>/<id:\d+>' => '<_c>/view',
//                '<_c:[\w-]+>/<id:\d+>/<_a:[\w-]+>' => '<_c>/<_a>',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['order', 'debt', 'stock', 'user', 'product', 'map', 'filter', 'complaint']],

            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@common/rbac/items.php',
            'assignmentFile' => '@common/rbac/assignments.php',
            'ruleFile' => '@common/rbac/rules.php',
            'defaultRoles' => ['author'],
        ],
    ],
    'params' => $params,
];