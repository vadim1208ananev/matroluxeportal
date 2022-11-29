<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class OrderProductNostandart extends ActiveRecord
{

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

    public function getProductDesc()
    {
        return $this->hasOne(ProductDesc::className(), ['product_id' => 'product_id']);
    }

}
