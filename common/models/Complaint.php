<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Complaint extends ActiveRecord
{
    public $sameAddress;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

    public function getProductSize()
    {
        return $this->hasOne(ProductSize::className(), ['size_id' => 'size_id', 'product_id' => 'product_id']);
    }

    public function cleanIfSameAddress()
    {
        if ($this->sameAddress) {
            $this->delivery_service_id_to = null;
            $this->service_type_to = '';
            $this->city_to = '';
            $this->warehouse_to = '';
            $this->street_to = '';
            $this->building_to = '';
            $this->flat_to = '';
            $this->city_ref_to = '';
            $this->warehouse_ref_to = '';
            $this->street_ref_to = '';
        }
        if (empty($this->phone_extra)) {
            $this->phone_extra_prefix = '';
        }
    }

    public function fillInDescription()
    {
        $desc = "Забрать с: \n";
        $desc .= Delivery::VALUES[$this->delivery_service_id] . ', ';
        $desc .= Delivery::SERVICE_TYPES[$this->service_type] . ', ';
        $desc .= $this->city . ', ';
        $desc .= $this->service_type == CartForm::WAREHOUSEWAREHOUSE
            ? $this->warehouse . "\n"
            : ($this->service_type == CartForm::WAREHOUSEDOORS ? $this->building . ', ' . $this->flat : '') . "\n";
        if ($this->sameAddress == false) {
            $desc .= "Вернуть на: \n";
            $desc .= Delivery::VALUES[$this->delivery_service_id_to] . ', ';
            $desc .= Delivery::SERVICE_TYPES[$this->service_type_to] . ', ';
            $desc .= $this->city_to . ', ';
            $desc .= $this->service_type_to == CartForm::WAREHOUSEWAREHOUSE
                ? $this->warehouse_to
                : ($this->service_type_to == CartForm::WAREHOUSEDOORS ? $this->building_to . ', ' . $this->flat_to : '');
        } else {
            $desc .= "Вернуть на: \n--такой же--";
        }
        $this->description = $desc;
    }

}
