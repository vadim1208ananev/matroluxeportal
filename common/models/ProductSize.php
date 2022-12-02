<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ProductSize extends ActiveRecord
{

    public function getSize()
    {
        return $this->hasOne(Size::className(), ['size_id' => 'size_id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }

    public static function getProductSizes($productIds)
    {
        $productSizes = ProductSize::find()
            ->with(['size.sizeDesc'])
//            ->where(['is not', '1c_id', null])
            ->andWhere(['in', 'product_id', $productIds])
            ->orderBy(['product_id' => SORT_ASC])
            ->asArray()
            ->all();
         //   dd($productSizes);
        $sizes = array_reduce($productSizes, function ($carry, $item) {
            $carry[$item['product_id']][$item['size']['sizeDesc']['size_id']] = [
                'sort_order' => $item['size']['sort_order'],
                'size_id' => $item['size']['sizeDesc']['size_id'],
                'name' => $item['size']['sizeDesc']['name'],
                'price' => $item['price'] ? round($item['price']) . ' грн' : '',
            ];
            return $carry;
        }, []);
        $sizes = array_map(function ($item) {
            uasort($item, function ($a, $b) {
                if ($a['sort_order'] == $b['sort_order'])
                    return 0;
                return $a['sort_order'] > $b['sort_order'] ? 1 : -1;
            });
            return $item;
        }, $sizes);
        return $sizes;
    }

}
