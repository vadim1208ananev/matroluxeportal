<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class Attr extends ActiveRecord
{

    const ATTR_DISCOUNT_ID = 92;
    const ATTR_IN_STYLE = 95;
    const ATTR_MATTRESS_PROTECTION_ID = 82;
    const WARDROBE_CLASSIC_ID = 150;
    const WARDROBE_STANTART_ID = 151;
    const WARDROBE_ONE_DOORS_ID = 277;
    const WARDROBE_THREE_DOORS_ID = 153;
    const WARDROBE_FOUR_DOORS_ID = 253;
    const WARDROBE = 223;

    public $wardrobeTypes = [
        self::WARDROBE_CLASSIC_ID => WardrobeDoor::CLASSIC_ID,
        self::WARDROBE_STANTART_ID => WardrobeDoor::STANDART_ID,
    ];

    public function getAttrDesc()
    {
        return $this->hasOne(AttrDesc::className(), ['attr_id' => 'attr_id'])->
        OnCondition(['attr_desc.lang_id' => Yii::$app->language]);
    }

    public function getAttrGroup()
    {
        return $this->hasOne(AttrGroup::className(), ['attr_group_id' => 'attr_group_id']);
    }

    public function getProductAttrs()
    {
        return $this->hasMany(ProductAttr::className(), ['attr_id' => 'attr_id']);
    }

    public static function getAttrGroupIds($attrIds)
    {
        $attr = Attr::find()
            ->select('attr_group_id')
            ->where(['in', 'attr_id', $attrIds])
            ->distinct()
            ->asArray()
            ->all();
        return array_reduce($attr, function ($carry, $item) {
            $carry[] = reset($item);
            return $carry;
        }, []);
    }

    public static function getAttrs($attrGroupIds, $attrIds)
    {
        $filter = Yii::$app->request->get('f');
        $filter = $filter ? self::parseFilterUrl($filter) : [];
        $attrs = AttrGroup::find()
            ->joinWith('attrGroupDesc')
//            ->joinWith('attrs')
            ->joinWith([
                'attrs' => function ($q) use ($attrIds) {
                    $q->andWhere(['in', 'attr.attr_id', $attrIds]);
                }
            ])
//            ->joinWith('attrDesc')
            ->joinWith([
                'attrDesc' => function ($q) use ($attrIds) {
                    $q->andWhere(['in', 'attr_desc.attr_id', $attrIds]);
                }
            ])
            ->where(['in', 'attr_group.attr_group_id', $attrGroupIds])
//            ->andWhere(['in', 'attr.attr_id', $attrIds])
            ->asArray()
            ->all();
        $attrs = self::getFilledAttrs($attrs);     
        $attrs = self::getSortedData($attrs);
      
        $filter = self::addSortOrderFilter($attrs, $filter);

        $filter = self::getSortedData($filter);
        if ($filter) {
            $attrs = self::getDeletedAttrs($attrs, $attrIds);
        }
       
        foreach ($attrs as &$attr) {
            foreach ($attr['attrs'] as &$a) {
                //f=category=1:bespruzhinnye-matrasy=1,pruzhinnye-matrasy=2;tip-matrasa=2:dvustoronniy=11,odnostoronniy=12
                $filterCopy = $filter;
                $agUrl = $attr['url'];
                $aUrl = $a['url'];
                $agId = $attr['attr_group_id'];
                $aId = $a['attr_id'];
                $a['url'] = self::createFilterUrl($filterCopy, $attrs, $agUrl, $aUrl, $agId, $aId);
                $a['url_raw'] = $aUrl;
                if (is_array($filterCopy) && array_key_exists($agId, $filterCopy) && array_key_exists($aId, $filterCopy[$agId]['attrs'])) {
                    $a['selected'] = true;
                } else {
                    $a['selected'] = false;
                }
                $a['css'] = ($a['attr_id'] == self::ATTR_DISCOUNT_ID || $a['attr_id'] == self::ATTR_IN_STYLE) ? 'is-danger has-text-weight-bold' : 'is-primary';
            }
            unset($a);
        }
        unset($attr);

        return $attrs;
    }

    public static function parseFilterUrl($filter)
    {
        $filter = explode(';', $filter);
        //f=category=1:bespruzhinnye-matrasy=1,pruzhinnye-matrasy=2;tip-matrasa=2:dvustoronniy=11,odnostoronniy=12
        $filter = array_reduce($filter, function ($carry1, $item1) {
            $ag = explode(':', $item1); //category=1:bespruzhinnye-matrasy=1,pruzhinnye-matrasy=2
            $a = explode(',', $ag[1]); //bespruzhinnye-matrasy=1,pruzhinnye-matrasy=2
            $a = array_reduce($a, function ($carry2, $item2) {
                $carry2[explode('=', $item2)[1]] = [
                    'attr_id' => explode('=', $item2)[1],
                    'url' => explode('=', $item2)[0]
                ];
                return $carry2;
            }, []);
            $carry1[explode('=', $ag[0])[1]] = [
                'attr_group_id' => explode('=', $ag[0])[1],
                'url' => explode('=', $ag[0])[0],
                'attrs' => $a];
            return $carry1;
        }, []);
        return $filter;
    }

    public static function createFilterUrl($filter, $attrs, $agUrl, $aUrl, $agId, $aId)
    {
        //f=category=1:bespruzhinnye-matrasy=1,pruzhinnye-matrasy=2;tip-matrasa=2:dvustoronniy=11,odnostoronniy=12
        $request = Yii::$app->request;
        $get = Yii::$app->request->get();
//        $url = '/?f=';
        $url = '';
        if ($filter == null) {
//            return "{$url}{$agUrl}={$agId}:{$aUrl}={$aId}";
            return urldecode(Url::to(['category/index',
                'category_id' => $get['category_id'],
                's1' => $request->get('s1', 'c'),
                's2' => $request->get('s2', $get['category_id']),
                'f' => "{$agUrl}={$agId}:{$aUrl}={$aId}",
            ]));
        }
        if (array_key_exists($agId, $filter)) {
            if (array_key_exists($aId, $filter[$agId]['attrs'])) {
                unset($filter[$agId]['attrs'][$aId]);
                if (empty($filter[$agId]['attrs']))
                    unset($filter[$agId]);
            } else {
                $filter[$agId]['attrs'][$aId] = [
                    'attr_id' => $aId,
                    'url' => $aUrl,
                    'sort_order' => $attrs[$agId]['attrs'][$aId]['sort_order']
                ];
            }
        } else {
            $filter[$agId] = [
                'attr_group_id' => $agId,
                'url' => $agUrl,
                'sort_order' => $attrs[$agId]['sort_order'],
                'attrs' => [
                    $aId => [
                        'attr_id' => $aId,
                        'url' => $aUrl,
                        'sort_order' => $attrs[$agId]['attrs'][$aId]['sort_order']
                    ]
                ]
            ];
        }

        if ($filter == null) {
            return urldecode(Url::to(['category/index',
                'category_id' => $get['category_id'],
                's1' => $request->get('s1', 'c'),
                's2' => $request->get('s2', $get['category_id'])
            ]));
        } else {
            $filter = self::getSortedData($filter);
            foreach ($filter as $i => $ag) {
                $url .= "{$ag['url']}=$i:";
                foreach ($ag['attrs'] as $iId => $a) {
                    $url .= "{$a['url']}={$iId},";
                }
                $url = rtrim($url, ',');
                $url .= ';';
            }
        }
//        return rtrim($url, ';');
        $url = rtrim($url, ';');
        return urldecode(Url::to(['category/index',
            'category_id' => $get['category_id'],
            's1' => $request->get('s1', 'c'),
            's2' => $request->get('s2', $get['category_id']),
            'f' => $url,
        ]));
    }

    public static function getDeletedAttrs($attrs, $attrIds)
    {
        foreach ($attrs as $i1 => &$attr) {
            foreach ($attr['attrs'] as $i2 => $a) {
                if (!in_array($a['attr_id'], $attrIds)) {
                    unset($attrs[$i1]['attrs'][$i2]);
                    unset($attrs[$i1]['attrDesc'][$i2]);
                }
            }
        }
        return $attrs;
    }

    public static function getFilledAttrs(&$attrs)
    {

        $attrs = array_combine(array_column($attrs, 'attr_group_id'), array_values($attrs));
      //  dd($attrs);
        foreach ($attrs as $i1 => &$attr) {
            foreach ($attr['attrs'] as $i2 => $a) {
                $attrs[$i1]['attrs'][$i2]['name'] = $attrs[$i1]['attrDesc'][$i2]['name'];
            }
            $attr['attrs'] = array_combine(array_column($attr['attrs'], 'attr_id'), array_values($attr['attrs']));
        }
        return $attrs;
    }

    public static function addSortOrderFilter($attrs, &$filter)
    {
        foreach ($filter as $agId => &$ag) {
            $ag['sort_order'] = $attrs[$agId]['sort_order'];
            foreach ($ag['attrs'] as $aId => &$a) {
                $a['sort_order'] = $attrs[$agId]['attrs'][$aId]['sort_order'];
            }
        }
        return $filter;
    }

    public static function getSortedData(&$array)
    {
        uasort($array, function ($a, $b) {
            if ($a['sort_order'] == $b['sort_order'])
                return 0;
            return $a['sort_order'] > $b['sort_order'] ? 1 : -1;
        });
        $array = array_map(function ($item) {
            uasort($item['attrs'], function ($a, $b) {
                if ($a['sort_order'] == $b['sort_order'])
                    return 0;
                return $a['sort_order'] > $b['sort_order'] ? 1 : -1;
            });
            return $item;
        }, $array);
        return $array;
    }
}
