<?php

namespace app\modules\backend\controllers;

use common\models\User;
use common\models\WardrobeDoor;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class ServiceController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'import-wardrobe-door', 'calc'],
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                ],
            ],
        ];
    }

    public function actionImportWardrobeDoor()
    {

//        ini_set('memory_limit', '1024MB');

//        $root = new WardrobeDoor(['name' => 'Двері НОВІ', '1c_id' => 'da851236-fc6f-11e6-ae58-18fb7baa207a']);
//        $root->makeRoot();
//        return;

        $parents = [];

        $root = WardrobeDoor::findOne(1);
        if ($root) {
            $parents[$root['1c_id']] = $root;
        }

        $file = fopen('/home/oleg-a/Downloads/doors1.csv', 'r');
        while (($data = fgetcsv($file)) !== FALSE) {
            $hasParent = true;
            $parentName = trim($data[0]);
            $parentUid = $data[1];
            $itemName = trim($data[2]);
            $itemUid = $data[3];

            if (!array_key_exists($parentUid, $parents)) {
                $hasParent = false;
                echo 'ParentUid does not exist: ' . $parentName . ' ' . $parentUid . PHP_EOL;
            }

            $item = WardrobeDoor::findOne(['name' => $itemName, '1c_id' => $itemUid]);
            if ($hasParent && !$item) {
                $item = new WardrobeDoor(['name' => $itemName, '1c_id' => $itemUid]);
                $item->appendTo($parents[$parentUid]);
            };
            if (!array_key_exists($itemUid, $parents)) {
                $parents[$itemUid] = $item;
            }
            unset($item);
        }

        Yii::$app->end();
    }

}
