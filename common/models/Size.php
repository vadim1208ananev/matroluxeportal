<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Size extends ActiveRecord
{
    const STANDART_SIZE_ID = 125;

    public function getSizeDesc()
    {
        return $this->hasOne(SizeDesc::className(), ['size_id' => 'size_id'])->
        onCondition(['lang_id' => Yii::$app->language]);
    }

    public static function isStandart($sizeId)
    {
        //1 (int) - standart size
        //100x120 (string) - nostandart size
        //"1 000, 600, 2 100, біле дерево, бронза БАВАРІЯ, С1 КЛ, С1 КЛ" (string) - nostandart size (wardrobe)
        return preg_match('/^\d+$/', $sizeId);
    }

    public static function isUuid($size1cId)
    {
        //f267c9a0-f6dc-11e8-af79-18fb7baa207a
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $size1cId);
    }

}
