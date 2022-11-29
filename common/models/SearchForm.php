<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SearchForm extends Model
{
    public $q;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['q', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'q' => 'Текст для поиска',
        ];
    }
}