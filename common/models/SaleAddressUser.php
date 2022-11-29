<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class SaleAddressUser extends ActiveRecord
{
    public function getStatus()
    {
        return $this->hasOne(SaleAddressStatus::className(), ['id' => 'status_id']);
    }

    public function getType()
    {
        return $this->hasOne(SaleAddressType::className(), ['id' => 'type_id']);
    }
}
