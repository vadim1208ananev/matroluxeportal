<?php

namespace app\modules\backend\controllers;

use common\models\Complaint;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\data\Pagination;
use yii\web\ForbiddenHttpException;

/**
 * Site controller
 */
class ComplaintController extends Controller
{

    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = 'backend/login';
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'show', 'send'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewComplaint')) {
            throw new ForbiddenHttpException();
        }
        $query = Complaint::find()
            ->orderBy([
                'complaint_id' => SORT_DESC
            ]);

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 4]);
        $pages->pageSizeParam = false;
        $complaints = $query->offset($pages->offset)->limit($pages->limit)->all();
        $data['complaints'] = $complaints;
        $data['pages'] = $pages;



        return $this->render('index', $data);
    }

    public function actionShow($complaint_id)
    {
        if (!Yii::$app->user->can('viewComplaint')) {
            throw new ForbiddenHttpException();
        }
        //  return $complaint_id;
        $dir = Yii::getAlias('@frontend') . '/web/uploads/';
        $images = glob($dir . '*');
        $images = array_map(function ($item) use ($complaint_id) {
            $file = basename($item);
            $pattern = '/^' . $complaint_id . '_/';
            if (preg_match($pattern, $file)) {
                return '/uploads/' . $file;
            }
        }, $images);
        $images = array_filter($images);

        return $this->render('show', [
            'images' => $images,
        ]);
    }
    public function out($status, $data)
    {
        $item = [
            'status' => $status,
            'data' => $data
        ];
        return json_encode($item);
    }


    public function actionSend()
    {
        sleep(3);
        $post = Yii::$app->request->post();
        if (isset($post['complaint_id'])) {
            $complaint_id = $post['complaint_id'];
            return $this->out('ok', $complaint_id);
        } else {
            return $this->out('error', []);
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    /*  public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    /* public function actionLogout()
    {
        Yii::$app->user->logout();


        $this->redirect('/backend');
    }*/
}
