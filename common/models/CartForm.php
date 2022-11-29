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
class CartForm extends Model
{
    const WAREHOUSEDOORS = 'WarehouseDoors';
    const WAREHOUSEWAREHOUSE = 'WarehouseWarehouse';

    public $isDelivery;
    public $deliveryService;
    public $telephone;
    public $lastName; //фамилия
    public $firstName; //имя
    public $middleName; //отчество
    public $serviceType; //WarehouseWarehouse, WarehouseDoors

    public $city;

    public $street;
    public $building;
    public $flat;

    public $warehouse;

    public $cityRef;
    public $streetRef;
    public $warehouseRef;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['isDelivery', 'flat', 'cityRef', 'streetRef', 'warehouseRef'], 'safe'],
            ['deliveryService', 'required', 'when' => function ($model) {
                return $model->isDelivery == true;
            }],
            ['telephone', 'required', 'when' => function ($model) {
                return $model->isDelivery == true;
            }],
            ['lastName', 'required', 'when' => function ($model) {
                return $model->isDelivery == true;
            }],
            ['firstName', 'required', 'when' => function ($model) {
                return $model->isDelivery == true;
            }],
            ['middleName', 'required', 'when' => function ($model) {
                return $model->isDelivery == true;
            }],
            ['serviceType', 'required', 'when' => function ($model) {
                return $model->isDelivery == true;
            }],
//            ['city', 'required', 'when' => function ($model) {
//                return $model->isDelivery == true;
//            }],
            ['city', 'validateCity', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['warehouse', 'validateWarehouse', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['street', 'validateStreet', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['building', 'validateBuilding', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'deliveryService' => 'Служба доставки',
            'telephone' => 'Телефон',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'serviceType' => 'Тип доставки',
            'city' => 'Нас. пункт',
            'street' => 'Улица',
            'building' => 'Дом',
            'flat' => 'Квартира',
            'warehouse' => 'Отделение',
            'cityRef' => 'Нас. пункт Ref',
            'streetRef' => 'Улица Ref',
            'warehouseRef' => 'Отделение Ref',
        ];
    }

    public function validateCity($attribute, $params)
    {
        $field = trim($this->city);
        if ($this->serviceType == self::WAREHOUSEWAREHOUSE && $this->isDelivery == 1
//            && (isset($field) === true && $field === '')
            && empty($this->cityRef)) {
            $this->addError($attribute, 'Необходимо выбрать «Нас. пункт» путем ввода не менее 3 первых букв.');
        }
    }

    public function validateWarehouse($attribute, $params)
    {
        $field = trim($this->warehouse);
        if ($this->serviceType == self::WAREHOUSEWAREHOUSE && $this->isDelivery == 1
//            && (isset($field) === true && $field === '')
            && empty($this->warehouseRef)) {
            $this->addError($attribute, 'Необходимо выбрать «Отделение» из выпадающего списка.');
        }
    }

    public function validateStreet($attribute, $params)
    {
        $field = trim($this->street);
        if ($this->serviceType == self::WAREHOUSEDOORS && $this->isDelivery == 1
//            && (isset($field) === true && $field === '')
            && empty($this->streetRef)) {
            $this->addError($attribute, 'Необходимо заполнить «Улицу» путем ввода не менее 3 первых букв.');
        }
    }

    public function validateBuilding($attribute, $params)
    {
        $field = trim($this->building);
        if ($this->serviceType == self::WAREHOUSEDOORS && $this->isDelivery == 1
            && (isset($field) === true && $field === '')) {
            $this->addError($attribute, 'Необходимо заполнить «Дом».');
        }
    }
}