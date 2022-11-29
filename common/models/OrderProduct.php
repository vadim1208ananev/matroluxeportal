<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class OrderProduct extends ActiveRecord
{
    public function rules()
    {
//        return [
//            [['order_id', 'product_id', 'attr_id', 'amount', 'price', 'sum', '1c_id'], 'safe']
//        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

    public function getAttr()
    {
        return $this->hasOne(Attr::className(), ['attr_id' => 'attr_id']);
    }

    public function getSize()
    {
        return $this->hasOne(Size::className(), ['size_id' => 'size_id']);
    }

    public function getProductSize()
    {
        return $this->hasOne(ProductSize::className(), ['size_id' => 'size_id', 'product_id' => 'product_id']);
    }

    public function getProductDesc()
    {
        return $this->hasOne(ProductDesc::className(), ['product_id' => 'product_id']);
    }

    public function getSizeDesc()
    {
        return $this->hasOne(SizeDesc::className(), ['size_id' => 'size_id']);
    }
}
