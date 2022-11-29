<?php


namespace console\controllers;

use yii\console\Controller;

class CrawlerController extends Controller
{
    public $message;

    public function options($actionID)
    {
        return ['message'];
    }

    public function optionAliases()
    {
        return ['m' => 'message'];
    }

    public function actionIndex()
    {
        echo $this->message . "\n";
        echo 123123;
        echo PHP_EOL;
    }
}