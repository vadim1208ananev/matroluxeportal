<?php

namespace common\models;

use yii\db\ActiveRecord;

class Warehouse extends ActiveRecord
{
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['warehouse_id' => 'warehouse_id']);
    }
}
