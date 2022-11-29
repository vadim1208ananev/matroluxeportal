<?php

namespace common\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;

class WardrobeDoor extends ActiveRecord
{

    const CLASSIC_ID = 2;
    const STANDART_ID = 6154;

    public $children = [];
    public $path;
    public $showImage;

    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                // 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new WardrobeDoorQuery(get_called_class());
    }

    public static function findAttrIds($attrGroupIds = [])
    {
        $arr = Attr::find()
            ->where(['in', 'attr_group_id', $attrGroupIds])
            ->orderBy(['sort_order' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public function afterFind()
    {
        //todo check file extension's case
        $absPath = Yii::getAlias('@webroot') . '/images/store/WardrobeDoors/' . $this->name . '.jpg';
        if (file_exists($absPath)) {
            $this->path = '/images/store/WardrobeDoors/' . $this->name . '.jpg';
            $this->showImage = true;
        } else {
            $this->path = Yii::$app->params['main.path_no_image'];
            $this->showImage = false;
        }

        return parent::afterFind();
    }

}