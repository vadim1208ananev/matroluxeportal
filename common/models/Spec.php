<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Spec extends ActiveRecord
{
    //status
    //1 - Новый
    //2 - У менеджера

    public function rules()
    {
//        return [
//            [['order_id', 'user_id', '1c_number', 'sum', '1c_number'], 'safe']
//        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getSpecProducts()
    {
        return $this->hasMany(SpecProduct::className(), ['spec_id' => 'spec_id']);
    }

}
