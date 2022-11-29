<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AttrDesc extends ActiveRecord
{
//    public function getAttrGroup()
//    {
//        return $this->hasOne(AttrGroup::className(), ['attr_group_id' => 'attr_group_id']);
//    }

    public function getAttr()
    {
        return $this->hasOne(Attr::className(), ['attr_id' => 'attr_id']);
    }
}
