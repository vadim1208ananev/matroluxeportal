<?php

namespace frontend\controllers;

use common\models\News;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{

    public function actionView($news_id)
    {
        $data = [];
        $news = News::findOne($news_id);
        if ($news == null)
            throw new NotFoundHttpException("Object not found: $news_id");
        $data['news'] = $news;

        return $this->render('view', $data);
    }
}
