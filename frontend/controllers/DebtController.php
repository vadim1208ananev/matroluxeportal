<?php

namespace frontend\controllers;

use common\models\Debt;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;

/**
 * Site controller
 */
class DebtController extends Controller
{

    /**
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $data = [];

        $debtsResult = Debt::find()
            ->select(['user_id', 'COUNT(*) as count', 'SUM(debt) as debt', 'SUM(debt_overdue) as debt_overdue'])
            ->where(['user_id' => Yii::$app->user->getId()])
            ->groupBy(['user_id'])
            ->asArray()
            ->all();

        $debtsQuery = Debt::find()
            ->where(['user_id' => Yii::$app->user->getId()])
            ->orderBy('shipped_at DESC');

        $pages = new Pagination(['totalCount' => $debtsQuery->count(), 'pageSize' => 50]);
        $pages->pageSizeParam = false;
        $products = $debtsQuery->offset($pages->offset)->limit($pages->limit)->all();
        $data['products'] = $products;
        $data['pages'] = $pages;

        $data['debtsResult'] = $debtsResult;
        $data['debts'] = $debtsQuery->asArray()->all();
        return $this->render('index', $data);
    }

}
