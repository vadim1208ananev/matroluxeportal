<?php

namespace common\models;

use Yii;
use yii\base\Model;


class ComplaintForm extends Model
{
    public $last_name;
    public $first_name;
    public $middle_name;
    public $phone_prefix;
    public $phone;
    public $phone_extra_prefix;
    public $phone_extra;

    public $delivery_service_id; //'1' => 'Новая Почта', '2' => 'Мист-Экспресс'
    public $service_type; //WarehouseWarehouse, WarehouseDoors

    //address from
    public $city;
    public $street;
    public $building;
    public $flat;
    public $warehouse;
    public $city_ref;
    public $street_ref;
    public $warehouse_ref;

    public $product_cm_id;
    //address to
    public $city_to;
    public $street_to;
    public $building_to;
    public $flat_to;
    public $warehouse_to;
    public $city_ref_to;
    public $street_ref_to;
    public $warehouse_ref_to;

    public $delivery_service_id_to;
    public $service_type_to;

    public $comment;

    public $product_id;
    public $size_id;

    public $purchase_month;
    public $purchase_year;

    public $sameAddress = false;
    public $imageFiles = [];

    public static $operators = ['039', '050', '063', '066', '067', '068', '073', '091', '092', '094', '095', '096', '097', '098', '099'];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['product_cm_id', 'product_id'], 'validateProduct', 'skipOnEmpty' => false, 'skipOnError' => false],

                    [['last_name', 'first_name', 'middle_name', 'phone_prefix', 'phone',
                'delivery_service_id', 'service_type', 'service_type_to', 'delivery_service_id_to', 
 //               'product_id',
//                 'size_id', 
//                 'attr_ids'
                 'purchase_month', 'purchase_year'], 'required'],
            [['phone_extra_prefix', 'phone_extra', 'flat', 'city_ref', 'street_ref', 'warehouse_ref', 'same_Address',
                'flat_to', 'city_ref_to', 'street_ref_to', 'warehouse_ref_to', 'comment'], 'safe'],
            [['phone', 'phone_extra'], 'string', 'min' => 7, 'max' => 7],
            [['phone', 'phone_extra'], 'match', 'pattern' => '/^[0-9]+$/u', 'message' => 'Телефон может содержать только цифры.'],
            [['city', 'city_to'], 'validateCity', 'skipOnEmpty' => false, 'skipOnError' => false],
//            [['warehouse', 'warehouseTo'], 'validateWarehouse', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['street', 'street_to'], 'validateStreet', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['building', 'building_to'], 'validateBuilding', 'skipOnEmpty' => false, 'skipOnError' => false],

            
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'minFiles' => 1, 'maxFiles' => 10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'phone' => 'Телефон',
            'phone_extra' => 'Дополнительный телефон',
            'delivery_service_id' => 'Служба доставки',
            'service_type' => 'Тип доставки',

            'city' => 'Нас. пункт',
            'street' => 'Улица',
            'building' => 'Дом',
            'flat' => 'Квартира',
            'warehouse' => 'Отделение',
            'city_ref' => 'Нас. пункт Ref',
            'street_ref' => 'Улица Ref',
            'warehouse_ref' => 'Отделение Ref',
            'sameAddress' => 'После рекламации доставить на тот же адрес',

            'city_to' => 'Нас. пункт',
            'street_to' => 'Улица',
            'building_to' => 'Дом',
            'flat_to' => 'Квартира',
            'warehouse_to' => 'Отделение',
            'city_ref_to' => 'Нас. пункт Ref',
            'street_ref_to' => 'Улица Ref',
            'warehouse_ref_to' => 'Отделение Ref',
            'delivery_service_id_to' => 'Служба доставки',
            'service_type_to' => 'Тип доставки',

            'comment' => 'Кратное описание и суть брака',

            'product_id' => 'Модель',
            'size_id' => 'Размер',
            'purchase_month' => 'Месяц',
            'purchase_year' => 'Год',

            'imageFiles' => 'Загрузить фото (не менее 5 фото)',
        ];
    }

    public function validateCity($attribute, $params)
    {
        if ($attribute == 'city') {
            if (empty($this->city_ref)) {
                $this->addError($attribute, 'Необходимо выбрать «Нас. пункт» путем ввода не менее 3 первых букв.');
            }
        } elseif ($attribute == 'city_to') {
            if (empty($this->city_ref_to) && $this->sameAddress == false) {
                $this->addError($attribute, 'Необходимо выбрать «Нас. пункт» путем ввода не менее 3 первых букв.');
            }
        }
    }

    public function validateWarehouse($attribute, $params)
    {
        if ($attribute == 'warehouse') {
            if ($this->service_type == CartForm::WAREHOUSEWAREHOUSE && empty($this->warehouse_ref)) {
                $this->addError($attribute, 'Необходимо выбрать «Отделение» из выпадающего списка.');
            }
        } elseif ($attribute == 'warehouse_to') {
            if ($this->service_type_to == CartForm::WAREHOUSEWAREHOUSE && empty($this->warehouse_ref_to) && $this->sameAddress == false) {
                $this->addError($attribute, 'Необходимо выбрать «Отделение» из выпадающего списка.');
            }
        }
    }

    public function validateStreet($attribute, $params)
    {
        if ($attribute == 'street') {
            if ($this->service_type == CartForm::WAREHOUSEDOORS && empty($this->street_ref)) {
                $this->addError($attribute, 'Необходимо заполнить «Улицу» путем ввода не менее 3 первых букв.');
            }
        } elseif ($attribute == 'street_to') {
            if ($this->service_type_to == CartForm::WAREHOUSEDOORS && empty($this->street_ref_to) && $this->sameAddress == false) {
                $this->addError($attribute, 'Необходимо заполнить «Улицу» путем ввода не менее 3 первых букв.');
            }
        }
    }

    public function validateBuilding($attribute, $params)
    {
        if ($attribute == 'building') {
            if ($this->service_type == CartForm::WAREHOUSEDOORS && empty($this->building)) {
                $this->addError($attribute, 'Необходимо заполнить «Дом».');
            }
        } elseif ($attribute == 'buildingTo') {
            if ($this->service_type_to == CartForm::WAREHOUSEDOORS && empty($this->building_to) && $this->sameAddress == false) {
                $this->addError($attribute, 'Необходимо заполнить «Дом».');
            }
        }
    }
    public function validateProduct($attribute, $params)
    {
        if ($attribute=='product_id' || $attribute=='product_cm_id') {
            if (!$this->product_id && !$this->product_cm_id) {
                $this->addError($attribute, 'Необходимо выбрать хотя-бы один товар из любой категории');
            }
        }
    }

    public function upload($complaintId)
    {
        foreach ($this->imageFiles as $file) {
            //                $path = Yii::getAlias('@frontend') . '/web/uploads/' . $this->survey_id . '_' . $file->baseName . '.' . $file->extension;
            $path = Yii::getAlias('@frontend') . '/web/uploads/' . $complaintId . '_' . $file->baseName . '.' . $file->extension;
            $file->saveAs($path);
            @chmod($path, 0755);
        }
    }
}
