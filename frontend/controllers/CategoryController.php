<?php

namespace frontend\controllers;

use common\models\Attr;
use common\models\Category;
use common\models\Product;
use common\models\ProductSize;
use common\models\ProductAttr;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * Site controller
 */
class CategoryController extends Controller
{

    public function actionIndex($category_id)
    {
        $data = [];
        $query = Product::getProductsQuery([], $category_id);
        $products = $query->all();
        $productIds = Product::getProductIds($products);

        $category = Category::findOne($category_id);
        $data['title'] = $category->name;

        $get = Yii::$app->request->get();
        if (isset($get['f'])) {
            $filter = Attr::parseFilterUrl($get['f']);
            $attrProducts = ProductAttr::getAttrProducts($filter, $productIds);
            $productIds = ProductAttr::getProductIdsFilter($filter, $attrProducts);
            $query = Product::getProductsQuery($productIds, $category_id);
        }

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 12]);
        $pages->pageSizeParam = false;
        $products = $query->offset($pages->offset)->limit($pages->limit)->with('productDiscount')->all();
        $data['products'] = $products;
        $data['pages'] = $pages;

        $data['sizes'] = ProductSize::getProductSizes(Product::getProductIds($products)); //чтобы не делать лишнюю работу, ограничиваем пагинацией

        $attrIds = ProductAttr::getAttrIds($productIds);
        $attrGroupIds = Attr::getAttrGroupIds($attrIds);
        $data['attrs'] = Attr::getAttrs($attrGroupIds, $attrIds);
        $data['indexPage'] = Url::to(['category/index', 'category_id' => $get['category_id'], 's1' => $get['s1'], 's2' => $get['s2']]);

        if ($category_id != 3) {
			//print_r(111);
            return $this->render('index', $data);
        } else {
            return $this->render('wardrobe', $data);
        }
    }
}
