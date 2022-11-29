<?php

namespace api\controllers;

use common\models\Post;
use common\models\SaleAddressStatus;
use common\models\SaleAddressType;
use common\models\SaleAddressUser;
use common\models\User;
use common\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\httpclient\Client;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class MapController extends ActiveController
{
    public $modelClass = 'common\models\SaleAddressUser';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

//        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        return $actions;
    }

    public function actionUpdate()
    {
        try {
            $bodyParams = Yii::$app->getRequest()->getBodyParams();
            $items = $bodyParams['items'];
            $addresses = SaleAddressUser::find()
                ->indexBy('hash')
                ->all();
            $statuses = SaleAddressStatus::find()
                ->indexBy('status')
                ->all();
            $types = SaleAddressType::find()
                ->indexBy('type')
                ->all();
//            Yii::info(count($items), 'debug');
            foreach ($items as $item) {
                $rawAddress = implode(' ', [$item['region'], $item['city'], $item['address']]);
                $address = str_replace(['№', '.', ',', '"', '\'', '«', '»', '“', '”', '(', ')'], '', $rawAddress);
                $address = str_replace('¶', ' ', $address);
                $address = preg_replace('/\s+/', ' ', $address);
                $address = str_replace(' ', '+', $address);
                $hash = md5($item['username'] . $address);
                $statusId = ($item['status'] == '' ? null : $statuses[$item['status']]->id);
                $typeId = ($item['type'] == '' ? null : $types[$item['type']]->id);

                $model = SaleAddressUser::findOne(['hash' => $hash]);
                if ($model) {
                    if (($model->status_id != $statusId)
                        || ($model->type_id != $typeId)) {
                        $model->status_id = $statusId;
                        $model->type_id = $typeId;
                        $model->save(false);
                    }
                }

                if (key_exists($hash, $addresses))
                    continue;

                $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=AIzaSyDhwebuNT8KmXrNxFUa_bWRt14y2OScgMo&language=ru';
                try {
                    $client = new Client(['transport' => 'yii\httpclient\CurlTransport']); // only cURL supports the options we need
                    $response = $client->createRequest()
                        ->setMethod('get')
                        ->setUrl($url)
                        ->send();
                } catch (\Exception $e) {
                    return ['status' => false, 'message' => $e->getMessage(), 'line' => $e->getLine()];
                }
                $responseData = $response->data;
                if ($responseData['status'] == 'OK') {
                    $model = new SaleAddressUser();
                    $model->username = $item['username'];
                    $model->user_1c_id = $item['user_1c_id'];
                    $model->raw_address = $rawAddress;
                    $model->formatted_address = $responseData['results'][0]['formatted_address'];
                    $model->lat = $responseData['results'][0]['geometry']['location']['lat'];
                    $model->lng = $responseData['results'][0]['geometry']['location']['lng'];
                    $model->place_id = $responseData['results'][0]['place_id'];
                    $model->hash = $hash;
                    $model->status_id = $statusId;
                    $model->type_id = $typeId;
                    $model->save(false);
                } else {
                    return $responseData;
                }

            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'line' => $e->getLine()];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'line' => $e->getLine()];
        }
        return [
            'status' => true
        ];
    }

    public function verbs()
    {
        return [
            'update' => ['put', 'patch'],
        ];
    }
}