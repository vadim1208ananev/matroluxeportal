<?php


namespace frontend\widgets;

use common\models\SearchForm;
use yii\base\Widget;

class SearchWidget extends Widget
{
    public function run()
    {
        return $this->render('search', [
            'model' => new SearchForm(),
        ]);
    }
}