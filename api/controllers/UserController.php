<?php

namespace api\controllers;

use common\models\Post;
use common\models\User;
use common\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

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
        unset($actions['index'], $actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        //sites/api/users

        $data = [];
        $get = Yii::$app->request->get();
        $user = Yii::$app->user->getIdentity();
        $isAdmin = $user->isAdmin();

        //getuser
        //sites/api/users?method=getuser
        if (isset($get['method']) && ($get['method'] == 'getuser')) {
            return [
                'companyname' => $user->companyname,
                'username' => $user->username,
                'email' => $user->email,
                'retrobonus' => $user->retrobonus,
                'bonus' => $user->bonus,
                'manager' => $user->manager,
                'manager_phone' => $user->manager_phone
            ];
        }

        //without method
        if (!$isAdmin)
            throw new ForbiddenHttpException('You are not allowed to access this data.');

        $users = User::find()
            ->all();
        foreach ($users as $u) {
            $data[] = [
                'email' => $u->email,
                'okpo' => $u->okpo
            ];
        }
        return $data;
    }

    public function actionUpdate()
    {
        try {
            $bodyParams = Yii::$app->getRequest()->getBodyParams();
            $users = $bodyParams['users'];
            foreach ($users as $user) {
                $model = User::findOne(['email' => $user['email']]);
                if ($model) {
                    if (($model->retrobonus != $user['retrobonus'] && $user['retrobonus'] != 0)
                        || $model->manager != $user['manager']
                        || $model['manager_phone'] != $user['manager_phone']) {
                        $model->retrobonus = $user['retrobonus'];
                        $model->manager = $user['manager'];
                        $model['manager_phone'] = $user['manager_phone'];
                        $model->save();
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
        }
        return [
            'status' => true
        ];
    }

    public
    function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }
}