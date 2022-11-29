<?php

namespace common\models;

use Yii;
use yii\base\Model;

class PaymentForm extends Model
{
    public $orderSum;
    public $bonusAvailable = 0;
    public $bonus = 0;
    public $cash = 0;

    public function __construct($orderSum, $bonusAvailable, $config = [])
    {
        $this->orderSum = $orderSum;
        $this->bonusAvailable = $bonusAvailable;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['orderSum', 'required'],
            ['bonus', function ($attribute, $params) {
                if ($this->bonus > $this->bonusAvailable) {
                    $this->addError($attribute, 'Превышен бонус.');
                }
                if ($this->bonus <= 0) {
                    $this->addError($attribute, 'Заполните бонус.');
                }
            }],
            ['cash', function ($attribute, $params) {
                if (($this->orderSum - $this->bonus) != $this->cash) {
                    $this->addError($attribute, 'Общая сумма оплаты не соответствует сумме заказа.');
                }
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bonus' => 'Бонусами',
            'cash' => 'За наличные',
        ];
    }
}