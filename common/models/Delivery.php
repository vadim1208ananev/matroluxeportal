<?php

namespace common\models;

use yii\db\ActiveRecord;

class Delivery extends ActiveRecord
{
    const NOVA_POSHTA_ID = 1;
    const MEEST_EXPRESS_ID = 2;

    const VALUES = [
        self::NOVA_POSHTA_ID => 'Новая почта',
        self::MEEST_EXPRESS_ID => 'Мист экспресс',
    ];

    const SERVICE_TYPES = [
        CartForm::WAREHOUSEWAREHOUSE => 'отделение',
        CartForm::WAREHOUSEDOORS => 'адрес',
    ];
}
