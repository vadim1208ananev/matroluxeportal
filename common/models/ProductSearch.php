<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ProductSearch extends ActiveRecord
{
    public function rules()
    {
        return [
            [['search'], 'safe'],
        ];
    }
}

