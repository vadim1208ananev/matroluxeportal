<?php

namespace app\modules\backend\controllers;

use common\models\BonusIn;
use common\models\BonusOut;
use common\models\Order;
use common\models\SaleAddressStatus;
use common\models\SaleAddressType;
use common\models\SaleAddressUser;
use common\models\User;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;

class MapController extends Controller
{

    public static $emptyStatus = 'Не заполнен (Статус)';
    public static $emptyType = 'Не заполнен (Тип)';

    public function beforeAction($action)
    {
        if (in_array($action->id, ['index'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'map']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = $statuses = $colors = $types = [];

        $request = Yii::$app->request;
        if ($request->isPost) {
            $addresses = SaleAddressUser::find()
                ->select('id, username, lat, lng, status_id, type_id')
                ->with('status', 'type')
                ->asArray()
                ->all();
            foreach ($addresses as $item) {
//                $data[$item['id']] = [
                $data[] = [
                    'id' => $item['id'],
                    'checked' => true,
                    'username' => $item['username'],
                    'lat' => $item['lat'],
                    'lng' => $item['lng'],
                    'status_id' => $item['status_id'],
                    'status' => isset($item['status']) ? $item['status']['status'] : self::$emptyStatus,
                    'type_id' => $item['type_id'],
                    'type' => isset($item['type']) ? $item['type']['type'] : self::$emptyType
                ];
            }

            foreach (SaleAddressStatus::find()
                         ->asArray()
                         ->all() as $item) {
                $statuses[$item['status']] = ['checked' => true, 'data' => []];
            }
            $statuses = array_merge($statuses, [self::$emptyStatus => ['checked' => true, 'data' => []]]);

            foreach (SaleAddressType::find()
                         ->asArray()
                         ->all() as $item) {
                $types[$item['type']] = ['checked' => true, 'data' => []];
            }
            $types = array_merge($types, [self::$emptyType => ['checked' => true, 'data' => []]]);

            $k = 1;

            return json_encode([
                'success' => true,
                'data' => $data,
                'statuses' => $statuses,
                'types' => $types
            ]);

        }

        return $this->render('index', $data);
    }

}
