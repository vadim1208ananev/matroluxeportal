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
class WardrobeDoorForm extends Model
{
    public $productId;
    public $width;
    public $depth;
    public $height;
    public $boardColor;
    public $profileColor;
    public $door1;
    public $door2;
    public $door3 = null;
    public $door4 = null;
    public $numberOfDoors;
    public $sameDoors;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['productId', 'width', 'depth', 'height', 'boardColor', 'profileColor', 'door1'], 'required'],
            [['sameDoors', 'numberOfDoors'], 'safe'],
            ['door2', 'required', 'when' => function ($model) {
                return $model->sameDoors != true && $model->numberOfDoors >= 2;
            }],
            ['door3', 'required', 'when' => function ($model) {
                return $model->sameDoors != true && $model->numberOfDoors >= 3;
            }],
            ['door4', 'required', 'when' => function ($model) {
                return $model->sameDoors != true && $model->numberOfDoors == 4;
            }],
//            ['door3', 'validateDoor3', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'width' => 'Ширина',
            'depth' => 'Глубина',
            'height' => 'Высота',
            'boardColor' => 'Цвет ДСП',
            'profileColor' => 'Цвет профиля',
            'door1' => 'Дверь 1',
            'door2' => 'Дверь 2',
            'door3' => 'Дверь 3',
            'door4' => 'Дверь 4',
            'sameDoors' => 'Другие двери как первая (флажок имеет приоритет над выбором других дверей)',
        ];
    }

//    public function validateDoor3($attribute, $params)
//    {
//        if ($this->hasThreeDoor == true) {
//            $this->addError($attribute, 'Необходимо заполнить «Дверь 3».');
//        }
//    }
}