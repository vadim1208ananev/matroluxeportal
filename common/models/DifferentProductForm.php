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
class DifferentProductForm extends Model
{
    public $productId;
    public $requiedFields;
    public $width;
    public $doubleWidth;
    public $depth;
    public $height;
    public $length;
    public $boardColor;
    public $mainColor;
    public $additionalColor;
    public $paintingType;
    public $woodType;
    public $mainWoodColor;
    public $additionalWoodColor;

    public static $values = [
        'Ширина' => 'width',
        'Ширина двойная' => 'doubleWidth',
        'Глубина' => 'depth',
        'Высота' => 'height',
        'Длина' => 'length',
        'Цвет ДСП' => 'boardColor',
        'Основной цвет' => 'mainColor',
        'Дополнительный цвет' => 'additionalColor',
        'Тип покраски' => 'paintingType',
        'Порода древесины' => 'woodType',
        'Основной цвет древесины' => 'mainWoodColor',
        'Дополнительный цвет древесины' => 'additionalWoodColor',
    ];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['productId'], 'required'],
            [['requiedFields'], 'safe'],
            ['width', 'required', 'when' => function ($model) {
                return in_array('width', $this->requiedFields);
            }],
            ['doubleWidth', 'required', 'when' => function ($model) {
                return in_array('doubleWidth', $this->requiedFields);
            }],
            ['depth', 'required', 'when' => function ($model) {
                return in_array('depth', $this->requiedFields);
            }],
            ['height', 'required', 'when' => function ($model) {
                return in_array('height', $this->requiedFields);
            }],
            ['length', 'required', 'when' => function ($model) {
                return in_array('length', $this->requiedFields);
            }],
            ['boardColor', 'required', 'when' => function ($model) {
                return in_array('boardColor', $this->requiedFields);
            }],
            ['mainColor', 'required', 'when' => function ($model) {
                return in_array('mainColor', $this->requiedFields);
            }],
            ['additionalColor', 'required', 'when' => function ($model) {
                return in_array('additionalColor', $this->requiedFields);
            }],
            ['paintingType', 'required', 'when' => function ($model) {
                return in_array('paintingType', $this->requiedFields);
            }],
            ['woodType', 'required', 'when' => function ($model) {
                return in_array('woodType', $this->requiedFields);
            }],
            ['mainWoodColor', 'required', 'when' => function ($model) {
                return in_array('mainWoodColor', $this->requiedFields);
            }],
            ['additionalWoodColor', 'required', 'when' => function ($model) {
                return in_array('additionalWoodColor', $this->requiedFields);
            }],
        ];
    }

    public function beforeValidate()
    {
        $this->requiedFields = explode(',', $this->requiedFields);
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'width' => 'Ширина',
            'doubleWidth' => 'Ширина двойная',
            'depth' => 'Глубина',
            'height' => 'Высота',
            'length' => 'Длина',
            'boardColor' => 'Цвет ДСП',
            'mainColor' => 'Основной цвет',
            'additionalColor' => 'Дополнительный цвет',
            'paintingType' => 'Тип покраски',
            'woodType' => 'Порода древесины',
            'mainWoodColor' => 'Основной цвет древесины',
            'additionalWoodColor' => 'Дополнительный цвет древесины',
        ];
    }
}