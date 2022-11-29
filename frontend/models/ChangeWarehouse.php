<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class ChangeWarehouse extends Model
{
    public $warehouse_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['warehouse_id', 'required'],
        ];
    }

    public function saveWarehouse()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user)
            return false;
        $user->warehouse_id = $this->warehouse_id;
        $user->save();
        return true;
    }

}
