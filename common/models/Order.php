<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{

    const ORDER_STATUS_SHIPPED = 4;

    //status
    //1 - У менеджера
    //2 - Обработан
    //3 - В производстве
    //4 - Отгружен

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

    public function getBonusIn()
    {
        return $this->hasOne(BonusIn::className(), ['order_id' => 'order_id']);
    }

    public function getBonusOut()
    {
        return $this->hasOne(BonusOut::className(), ['order_id' => 'order_id']);
    }

    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'order_id']);
    }

    public function getOrderProductNostandarts()
    {
        return $this->hasMany(OrderProductNostandart::className(), ['order_id' => 'order_id']);
    }

    public function getOrderPaid()
    {
        return $this->hasOne(OrderPaid::className(), ['order_id' => 'order_id']);
    }

    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['order_id' => 'order_id']);
    }

    public function extraFields()
    {
        return [
            'user' => 'user',
            'products' => 'orderProducts'
        ];
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return 'У менеджера';
            case 2:
                return 'Обработан';
            case 3:
                return 'В производстве';
            case 4:
                return 'Отгружен';
        }
        return '';
    }

    public function getAmount()
    {
        return OrderProduct::find()->where(['order_id' => $this->order_id])->sum('amount');
    }

}