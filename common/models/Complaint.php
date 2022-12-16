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
    public function getType()
    {
        $res = '';
        if ($this->product_id) {
            $res .= 'Матрасы';
        }
        if ($this->product_cm_id) {
            if (!$res) {
                $res .= 'Корпусная мебель';
            } else {
                $res .= '<br>,Корпусная мебель';
            }
        }
        return $res;
    }
    public function getProductcm()
    {
        //  return 999;
        return $this->hasOne(Product::className(), ['product_id' => 'product_cm_id']);
    }
    public function getArrtdata()
    {
        $arr = json_decode($this->attr_ids, 1);
        if (empty($arr)) return [];
        $arr = array_map(function ($item) {
            $attr = Attr::find()->where(['attr_id' => $item])->one();
            $attr_name = $attr->attrDesc->name;

            return [
                'attr_id' => $item,
                'attr_name' => $attr_name,
                'attr_1c_id'=>$attr['1c_id']

            ];
        }, $arr);
        return $arr;
    }
    public function getAttrs()
    {
        $arr = json_decode($this->attr_ids, 1);
        if (empty($arr)) return '-';

        $arr = array_map(function ($item) {
            $attr = Attr::find()->where(['attr_id' => $item])->one();
            $attr_name = $attr->attrDesc->name;
            $attr_group_name = $attr->attrGroup->attrGroupDesc->name;
            return $attr_group_name . ':' . $attr_name . '<br>';
        }, $arr);
        return implode(',', $arr);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['size_id' => 'size_id']);
    }
    public function getFio()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }
    public function getTel()
    {
        return  $this->phone_prefix . ' ' . $this->phone;
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
