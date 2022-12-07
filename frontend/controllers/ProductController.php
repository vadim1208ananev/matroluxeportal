<?php

namespace frontend\controllers;

use common\models\Attr;
use common\models\AttrGroup;
use common\models\NostandartOrderForm;
use common\models\Product;
use common\models\ProductSize;
use common\models\ProductAttr;
use common\models\WardrobeDoor;
use common\models\WardrobeDoorForm;
use common\services\Utils;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\DifferentProductForm;

/**
 * Site controller
 */
class ProductController extends Controller
{

    public function actionIndex($product_id)
    {
        $data = [];
        $request = Yii::$app->request;

        $query = Product::getProductsQuery([$product_id]);
        $product = $query->limit(1)->with('productDiscount')->one();
        if (!$product) //status = null
            throw new HttpException(404);
        $data['p'] = $product;

//        $productSizes = ProductSize::getProductSizesQuery($product_id)->asArray()->all();
//        $sizes = array_reduce($productSizes, function ($carry, $item) {
//            $carry[$item['product_id']][] = ['sort_order' => $item['size']['sort_order'], 'size_id' => $item['size']['sizeDesc']['size_id'], 'name' => $item['size']['sizeDesc']['name']];
//            return $carry;
//        }, []);
//        $sizes = array_map(function ($item) {
//            usort($item, function ($a, $b) {
//                if ($a['sort_order'] == $b['sort_order'])
//                    return 0;
//                return $a['sort_order'] > $b['sort_order'] ? 1 : -1;
//            });
//            return $item;
//        }, $sizes);
        $data['sizes'] = ProductSize::getProductSizes($product_id);

        $attrs = ProductAttr::getProductAttrs($product_id)
            ->all();
        $productAttrs = array_reduce($attrs, function ($carry, $item) {
            //$pa->attrDesc->attr->attrGroup->attrGroupDesc->name
            if ($item->attrDesc->attr->attrGroup->url == 'razmer-matrasa-(shhd)')
                return $carry;
            $carry[] = $item;
            return $carry;
        }, []);
        $data['productAttrs'] = $productAttrs;

        $isWardrobe = ProductAttr::find()
            ->where(['product_id' => $product->product_id, 'attr_id' => Attr::WARDROBE])
            ->exists();

        if ($product->category_id <= 2) {
          
            //not wardrobe
            $query = Product::getProductsQuery([], 2);
            $products = $query->all();

            $accs = ProductAttr::find()
                ->where(['in', 'attr_id', [82, 83, 90, 93]])
                ->with('attrDesc')
                ->with('product')
                ->all();
            $accs = array_reduce($accs, function ($carry, $item) {
                $carry[$item->attrDesc->name][] = $item->product;
                return $carry;
            }, []);
            $data['accs'] = $accs;
            $data['accSizes'] = ProductSize::getProductSizes(Product::getProductIds($products));
            $data['hasNostandart'] = Product::hasNostandart($product);
          //  dd($data['accSizes']);
            return $this->render('index', $data);
        } elseif ($product->category_id == 3 && $isWardrobe) {
           
            //wardrobe
//            https://github.com/creocoder/yii2-nested-sets

            $model = new WardrobeDoorForm();

            if ($request->isAjax && $model->load($request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $errors = ActiveForm::validate($model);
                return Json::encode([
                    'success' => count($errors) == 0,
                    'error' => $errors,
                    'data' => ArrayHelper::toArray($model),
                ]);
            }

            $hasOneDoor = $hasThreeDoor = $hasFourDoor = false;
            $profileBavaria = $profileOther = [];
            //classic or standart
            $parentId = 0;
            $wardrobeTypes = (new Attr())->wardrobeTypes;
            foreach ($productAttrs as $item) {
                if (array_key_exists($item->attr_id, $wardrobeTypes))
                    $parentId = $wardrobeTypes[$item->attr_id];
                if ($item->attr_id == ATTR::WARDROBE_ONE_DOORS_ID) $hasOneDoor = true;
                if ($item->attr_id == ATTR::WARDROBE_THREE_DOORS_ID) $hasThreeDoor = true;
                if ($item->attr_id == ATTR::WARDROBE_FOUR_DOORS_ID) $hasFourDoor = true;
            }


//            $attrGroups = AttrGroup::find()
//                ->with('attrGroupDesc', 'attrs', 'attrDesc')
//                ->where(['in', 'attr_group_id', [
//                    AttrGroup::WIDTH_ID,
//                    AttrGroup::DEPTH_ID,
//                    AttrGroup::HEIGHT_ID,
//                    AttrGroup::BOARD_COLOR_ID,
//                    AttrGroup::PROFILE_COLOR_ID,
//                ]])
//                ->asArray()
//                ->all();
//            foreach ($attrGroups as $group) {
//                $data['attrs'][$group['attrGroupDesc']['name']] = array_reduce($group['attrDesc'], function ($carry, $item) {
//                    $carry[$item['name']] = $item['name'];
//                    return $carry;
//                }, []);
//            }
            $ids = ArrayHelper::getColumn(ProductAttr::find()
                ->where(['product_id' => $product_id])
                ->asArray()
                ->all(), 'attr_id');

            $attrGroups = AttrGroup::find()
                ->with('attrGroupDesc')
                ->joinWith([
                    'attrs' => function ($query) use ($ids) {
                        $query->onCondition(['attr.attr_id' => $ids])
                            ->with('attrDesc');
                    }
                ])
                ->asArray()
                ->all();
            $attrs = [];
            foreach ($attrGroups as $key => $group) {
                if (count($group['attrs']) == 0
                    || in_array($group['attr_group_id'], [AttrGroup::CATEGORY, AttrGroup::SERIE, AttrGroup::NUMBER_OF_DOORS])) {
                    unset($attrGroups[$key]);
                    continue;
                }
                $attrs[$group['attrGroupDesc']['name']] = array_reduce($group['attrs'], function ($carry, $item) {
                    $carry[$item['attrDesc']['name']] = $item['attrDesc']['name'];
                    return $carry;
                }, []);
            }

            $k = 1;

            //1000 - 2000 - two doors, 2100 - 2400 - three doors
            foreach ($attrs['Ширина'] as $key => &$item) {
                if ($hasThreeDoor) {
                    if (intval(Utils::removeNbsp($item)) < 2100)
                        unset($attrs['Ширина'][$key]);
                } elseif ($hasFourDoor) {
                    if (intval(Utils::removeNbsp($item)) < 2500)
                        unset($attrs['Ширина'][$key]);
                } else {
                    if (intval(Utils::removeNbsp($item)) > 2000)
                        unset($attrs['Ширина'][$key]);
                }
                unset($item);
            }

            foreach ($attrs['Цвет профиля'] as $key => $item) {
                if (mb_strpos(mb_strtoupper($item), 'БАВАРІЯ') !== false) {
                    $profileBavaria[$key] = $item;
                } else {
                    $profileOther[$key] = $item;
                }
            }

            unset($attrs['Цвет профиля']);
            $attrs['Система Бавария'] = $profileBavaria;
            $attrs['Цвет профиля'] = $profileOther;

            $parent = WardrobeDoor::findOne($parentId);
            $children = $parent->children(1)->all();

            return $this->render('wardrobe', [
                'p' => $product,
                'attrs' => $attrs,
                'parent' => $parent,
                'children' => $children,
                'modelWardrobeForm' => $model,
                'numberOfDoors' => $hasOneDoor ? 1 : ($hasThreeDoor ? 3 : ($hasFourDoor ? 4 : 2)),
                'errors' => $model->errors,
            ]);
        } elseif ($product->category_id == 3 && !$isWardrobe) {
            //small cabinet furniture
  
            $requiedFields = [];
            $model = new DifferentProductForm();

            if ($request->isAjax && $model->load($request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $errors = ActiveForm::validate($model);
                return Json::encode([
                    'success' => count($errors) == 0,
                    'error' => $errors,
                    'data' => ArrayHelper::toArray($model),
                ]);
            }

            $ids = ArrayHelper::getColumn(ProductAttr::find()
                ->where(['product_id' => $product_id])
                ->asArray()
                ->all(), 'attr_id');
 
            $attrGroups = AttrGroup::find()
                ->with('attrGroupDesc')
                ->joinWith([
                    'attrs' => function ($query) use ($ids) {
                        $query->onCondition(['attr.attr_id' => $ids])
                            ->with('attrDesc');
                    }
                ])
                ->orderBy(['sort_order' => SORT_ASC])
                ->asArray()
                ->all();
              //  dd($ids,$attrGroups);
            $attrs = [];
            foreach ($attrGroups as $key => $group) {
                if (count($group['attrs']) == 0 || $group['attr_group_id'] == AttrGroup::CATEGORY) {
                    unset($attrGroups[$key]);
                    continue;
                }
                $attrs[DifferentProductForm::$values[$group['attrGroupDesc']['name']]] = array_reduce($group['attrs'], function ($carry, $item) {
                    $carry[$item['attrDesc']['name']] = $item['attrDesc']['name'];
                    return $carry;
                }, []);
                $requiedFields[] = DifferentProductForm::$values[$group['attrGroupDesc']['name']];
            }
    //dd($attrs);
            return $this->render('different_product', [
                'p' => $product,
                'attrs' => $attrs,
                'modelDifferentProductForm' => $model,
                'requiedFields' => implode(',', $requiedFields),
                'errors' => $model->errors,
            ]);
        }

    }

    public function actionGetNestedSetChildren()
    {
        $data = [];
        $request = Yii::$app->request;
        if (!$request->isPost) return json_encode(['success' => false, 'data' => 'The request must be POST-type.']);
        $parentId = $request->post('id');
        $parent = WardrobeDoor::findOne($parentId);
        $children = $parent->children(1)->all();

        //delete with name 'ЛОГОТИП', maybe needs removing from db
        foreach ($children as $key => &$item) {
            if (mb_strpos(mb_strtoupper($item['name']), 'ЛОГОТИП') !== false) {
                unset($children[$key]);
            }
        }
        foreach (array_values($children) as $item) {
            $data[] = [
                'path' => $item->path,
                'showImage' => $item->showImage,
                'id' => $item->id,
                'lft' => $item->lft,
                'rgt' => $item->rgt,
                'depth' => $item->depth,
                'name' => $item->name,
                '1c_id' => $item['1c_id'],
            ];
        }

        return json_encode([
            'success' => true,
            'hasLeaves' => $children && $children[0]->isLeaf(),
            'data' => Json::encode($data),
        ]);
    }



    public function actionGetProductSizes()
    {
        $post = Yii::$app->request->post();
        $data = ProductSize::getProductSizes([$post['productId']]);

        return json_encode(['success' => true, 'data' => $data,]);
    }
    public function actionGetProductAttributes()
    {
        $post = Yii::$app->request->post();
        if(!$post['selected_cm_value']) return;
        $product_id=$post['selected_cm_value'];
        $ids = ArrayHelper::getColumn(ProductAttr::find()
                ->where(['product_id' => $product_id])
                ->asArray()
                ->all(), 'attr_id');
 
            $attrGroups = AttrGroup::find()
                ->with('attrGroupDesc')
                ->joinWith([
                    'attrs' => function ($query) use ($ids) {
                        $query->onCondition(['attr.attr_id' => $ids])
                            ->with('attrDesc');
                    }
                ])
                ->orderBy(['sort_order' => SORT_ASC])
                ->asArray()
                ->all();
              // dd($ids,$attrGroups);
            $attrs = [];
            foreach ($attrGroups as $key => $group) {
                if (count($group['attrs']) <=1 || $group['attr_group_id'] == AttrGroup::CATEGORY) {
                    unset($attrGroups[$key]);
                    continue;
                }
                $attrs[$group['attrGroupDesc']['name']] = array_reduce($group['attrs'], function ($carry, $item) {
                    $carry[$item['attrDesc']['attr_id']] = $item['attrDesc']['name'];
                    return $carry;
                }, []);
                $requiedFields[] = DifferentProductForm::$values[$group['attrGroupDesc']['name']];
            }
    
       
        return json_encode(['success' => true, 'data' => $attrs,'product_id'=>$product_id]);;
       // $data = ProductSize::getProductSizes([$post['productId']]);

       // return json_encode(['success' => true, 'data' => $data,]);
    }


}
