<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AttrGroup extends ActiveRecord
{
    const CATEGORY = 1;
    const SERIE = 15;
    const NUMBER_OF_DOORS = 16;
    const ATTR_GROUP_RAZMER = 8;
    const WIDTH_ID = 10;
    const DEPTH_ID = 11;
    const HEIGHT_ID = 12;
    const BOARD_COLOR_ID = 13;
    const PROFILE_COLOR_ID = 14;

    public function getAttrGroupDesc()
    {
        return $this->hasOne(AttrGroupDesc::className(), ['attr_group_id' => 'attr_group_id'])
            ->OnCondition(['lang_id' => Yii::$app->language]);
    }

    public function getAttrs()
    {
        return $this->hasMany(Attr::className(), ['attr_group_id' => 'attr_group_id']);
    }

    public function getAttrDesc()
    {
        return $this->hasMany(AttrDesc::className(), ['attr_id' => 'attr_id'])
            ->viaTable('attr a', ['attr_group_id' => 'attr_group_id'])
            ->onCondition(['attr_desc.lang_id' => Yii::$app->language]);
    }
}
