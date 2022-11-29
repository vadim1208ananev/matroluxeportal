<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ProductDesc extends ActiveRecord
{
//    public function getProductAttrs()
//    {
//        return $this->hasMany(ProductAttr::className(), ['product_id' => 'product_id']);
//    }

    public function rules()
    {
        return [
            [['desc'], 'safe'],
        ];
    }

}
