<?php

namespace frontend\controllers;

use common\models\Debt;
use common\models\Stock;
use common\models\User;
use common\models\Warehouse;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;

/**
 * Site controller
 */
class StockController extends Controller
{

    /**
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $data = [];
        $stocks = [];
        $warehouse = Warehouse::find()
            ->where(['warehouse_id' => Yii::$app->user->identity->warehouse_id])
            ->one();
        if (!$warehouse)
            return $this->render('index', $data);
        $models = Stock::find()
            ->joinWith('product')
            ->joinWith('productDesc')
            ->joinWith('sizeDesc')
            ->where(['warehouse_id' => $warehouse->warehouse_id,])
            ->andWhere(['>', 'stock', 0])
            ->orderBy(['product_desc.name' => SORT_ASC])
            ->all();
        foreach ($models as $m) {
            $stocks[$m->product_id]['productDiscount'] = $m->product->productDiscount;
            $stocks[$m->product_id]['productId'] = $m->product_id;
            $stocks[$m->product_id]['productName'] = $m->productDesc->name;
            $stocks[$m->product_id]['sizes'][$m->size_id] = [
                'sizeId' => $m->size_id,
                'sizeName' => $m->sizeDesc->name,
                'stock' => $m->stock
            ];
        }
        $data['warehouse'] = $warehouse;
        $data['stocks'] = $stocks;
        $data['isDemo'] = User::isDemoModeByUsername(Yii::$app->user->getIdentity()->username);
        return $this->render('index', $data);
    }

}
