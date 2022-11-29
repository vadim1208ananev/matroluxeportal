<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Debt extends ActiveRecord
{
    public function rules()
    {
//        return [
//            [['order_id', 'user_id', '1c_number', 'sum', '1c_number'], 'safe']
//        ];
    }

//    public function behaviors()
//    {
//        return [
//            TimestampBehavior::className(),
//        ];
//    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function extraFields()
    {
        return [
            'user' => 'user',
        ];
    }
}
