<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class News extends ActiveRecord
{
    public function getNewsDesc()
    {
        return $this->hasOne(NewsDesc::className(), ['news_id' => 'news_id'])->
        OnCondition(['news_desc.lang_id' => Yii::$app->language]);
    }
}
