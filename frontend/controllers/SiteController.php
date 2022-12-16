<?php

namespace frontend\controllers;


use common\models\CartForm;
use common\models\Complaint;
use common\models\ComplaintForm;
use common\models\Product;
use common\models\ProductAttr;
use common\models\ProductSize;
use common\models\Size;
use common\models\SurveyUploadForm;
use common\models\User;
use common\models\Warehouse;
use Faker\Provider\Uuid;
use frontend\models\ChangeWarehouse;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Attr;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = [];
        //        $query = Product::getProductsQuery();
        //        $products = $query->all();
        //        $productIds = Product::getProductIds($products);
        //
        //        $filter = Yii::$app->request->get('f');
        //        if ($filter) {
        //            $filter = Attr::parseFilterUrl($filter);
        //            $attrProducts = ProductAttr::getAttrProducts($filter, $productIds);
        //            $productIds = ProductAttr::getProductIdsFilter($filter, $attrProducts);
        //            $query = Product::getProductsQuery($productIds);
        //        }
        //
        //        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 12]);
        //        $pages->pageSizeParam = false;
        //        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        //        $data['products'] = $products;
        //        $data['pages'] = $pages;
        //
        //        $data['sizes'] = ProductSize::getProductSizes(Product::getProductIds($products)); //чтобы не делать лишнюю работу, ограничиваем пагинацией
        //
        //        $attrIds = ProductAttr::getAttrIds($productIds);
        //        $attrGroupIds = Attr::getAttrGroupIds($attrIds);
        //        $data['attrs'] = Attr::getAttrs($attrGroupIds, $attrIds);

        return $this->render('index', $data);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionStart()
    {
        $products = Product::find()
            ->limit(10);
        $products = $products
            ->joinWith('productAttrs')
            ->with('productDesc')
            ->all();
        foreach ($products as $p) {
            echo $p->productDesc->name;
            foreach ($p->productAttrs as $pa) {
                echo $pa->attr_id;
            }
        }
        return $this->render('start');
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $html = '';
            $q = $request->get('q');
            $productsIds = Product::getProductIdsSearch($q, 10);
            $products = Product::getProductsSearchQuery($productsIds)->all();
            foreach ($products as $p) {
                $url = Url::to(['product/index', 'product_id' => $p->product_id, 's1' => $p->url, 's2' => 'p']);
                $html .= "<a class='panel-block' href='{$url}' title='{$p->productDesc->name}'";
                $html .= "<figure class='image'><img class='is-pulled-left' src='/{$p->getImage()->getPath('50x')}' alt='{$p->productDesc->name}' style='width: 50px; margin-right: 0.5em;'</figure>";
                $html .= "{$p->productDesc->name}</a>";
            }
            return $html;
        }

        $data = [];
        $q = $request->get('q');
        $data['q'] = $q;
        $productsIds = Product::getProductIdsSearch($q);
        $query = Product::getProductsSearchQuery($productsIds);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 12]);
        $pages->pageSizeParam = false;
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        $data['products'] = $products;
        $data['pages'] = $pages;

        $productIds = array_reduce($products, function ($carry, $item) {
            $carry[] = $item->product_id;
            return $carry;
        }, []);
        $data['sizes'] = ProductSize::getProductSizes($productIds);

        return $this->render('search', $data);
    }

    public function actionAccount()
    {
        $data = [];
        $data['user'] = Yii::$app->user->getIdentity();
        return $this->render('account', $data);
    }

    public function actionChangeWarehouse()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $data = [];
        $model = new ChangeWarehouse();
        $model->warehouse_id = Yii::$app->user->identity->warehouse_id;
        $warehouses = ArrayHelper::map(Warehouse::find()->all(), 'warehouse_id', 'name');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveWarehouse()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('changeWarehouse', [
            'model' => $model,
            'warehouses' => $warehouses
        ]);
    }

    public function actionOffline()
    {
        return $this->render('offline');
    }

    public function actionAboutUs()
    {
        return $this->render('aboutUs');
    }

    public function actionDostavkaIOplata()
    {
        return $this->render('dostavkaIOplata');
    }

    public function actionUsloviyaVozvrata()
    {
        return $this->render('usloviyaVozvrata');
    }

    public function actionDogovorOferty()
    {
        return $this->render('dogovorOferty');
    }

    public function actionThanks()
    {
        $this->layout = 'simple';
        return $this->render('thanks');
    }

    public function actionToken($token)
    {
        if (array_key_exists($token, Yii::$app->params['tokens']) == false)
            throw new \Exception('Token doesn\'t exist.');
        $action = Yii::$app->params['tokens'][$token]['action'];
        return $this->$action();
    }

    private function mattressesComplaint()
    {
        $this->layout = 'simple';
        $request = Yii::$app->request;
        // $complaint = new Complaint();

        $model = new ComplaintForm();
        $model->delivery_service_id = 1;
        $model->service_type = CartForm::WAREHOUSEWAREHOUSE;
        $model->delivery_service_id_to = 1;
        $model->service_type_to = CartForm::WAREHOUSEWAREHOUSE;
        $model->sameAddress = true;

        $model->last_name = 'Kovalenko';
        $model->first_name = 'Oleh';
        $model->middle_name = 'Anatoliyvish';
        $model->phone = '6384228';
        $model->comment = 'comment';


        if ($request->isPost) {
            $post = $request->post();
            // dd($post,UploadedFile::getInstances($model, 'imageFiles'));
            $model->load($post);
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->validate()) {

                $attributes = array_merge(json_decode(json_encode($post['ComplaintForm']), true), [
                    'delivery_service_id' => $post['delivery_serv'],
                    'delivery_service_id_to' => $post['delivery_serv_to'],
                    'attr_ids' => json_encode($post['attr_ids']),
                ]);
                unset($attributes['imageFiles']);

                //dd($attributes,$post,$model);

                $divided_attrs = $this->divideDataAttribute($attributes);;
                $complaint_ids = [];
                foreach ($divided_attrs as $divided_post) {
                    $complaint = new Complaint();
                    foreach ($divided_post as $index => $attribute) {
                        if (!$attribute) {
                            continue;
                        }
                        $complaint->{$index} = $attribute;
                    }
                    $complaint->product_name = Product::findOne($divided_post['product_id'])->name;
                    $complaint->size_name = Size::findOne($divided_post['size_id'])->name;
                    $complaint->cleanIfSameAddress();
                    $complaint->fillInDescription();
                    $complaint->save(false);
                    $complaint_ids[]=$complaint->complaint_id;
                  
                }
          
                $id_for_foto='vvv'.implode('vvv',$complaint_ids).'vvv';
  
                $model->upload($id_for_foto);
                $this->redirect(['thanks']);
            } else {
                $errors = $model->errors;
            }
        }

        $products = ArrayHelper::getColumn(Product::find()
            ->where(['category_id' => 1, 'status' => 1])
            ->indexBy('product_id')
            ->orderBy('name')
            ->asArray()
            ->all(), 'name');

        $products_cm = ArrayHelper::getColumn(Product::find()
            ->where(['category_id' => 3, 'status' => 1])
            ->indexBy('product_id')
            //  ->orderBy('name')
            ->asArray()
            ->all(), 'name');
        $products_cm = ['Выберите доступный вариант'] + $products_cm;
        $products = ['Выберите доступный вариант'] + $products;
        //  array_unshift($products_cm,'Выберите доступный вариант');
        // dd($products_cm);
        return $this->render('mattressesComplaint', [
            'model' => $model,
            'products' => $products,
            'products_cm' => $products_cm,
            'months' => ['янв', 'февр', 'март', 'апр', 'май', 'июн', 'июл', 'авг', 'сент', 'окт', 'ноя', 'дек'],
            'years' => range(2010, date('Y')),
        ]);
    }
    public function divideDataAttribute($attributes)
    {

        $arr_attributes = [];
        $need_arr = ['product_id', 'product_cm_id'];
        $need_size_attr = ['size_id', 'attr_ids'];
        if ($attributes['product_id'] && $attributes['size_id']) {
            $runtime_attributes = $attributes;
            /*    unset($runtime_attributes['product_cm_id']);
          unset($runtime_attributes['attr_ids']);*/
            $runtime_attributes['product_cm_id'] = '';
            $runtime_attributes['attr_ids'] = '';
            $arr_attributes[] = $runtime_attributes;
        }
        if ($attributes['product_cm_id'] && $attributes['attr_ids']) {
            $runtime_attributes = $attributes;
            /*unset($runtime_attributes['product_id']);
        unset($runtime_attributes['size_id']);*/
            $runtime_attributes['product_id'] = '';
            $runtime_attributes['size_id'] = '';
            $arr_attributes[] = $runtime_attributes;
        }

        return $arr_attributes;
    }

    public function actionService()
    {
        //        $k = 1;
        //
        //        $connection = Yii::$app->getDb();
        //        $command = $connection->createCommand("
        //            select o.order_id order_id, o.user_id, user_id, o.sum sum, o.status, DATE_FORMAT(FROM_UNIXTIME(o.created_at), '%Y-%m-%d') date
        //            from `order` o
        //            where DATE_FORMAT(FROM_UNIXTIME(o.created_at), '%Y-%m-%d') >= '2021-11-22'
        //              and DATE_FORMAT(FROM_UNIXTIME(o.created_at), '%Y-%m-%d') <= '2021-11-29'
        //              and o.status = 4
        //        ");
        //
        //        $result = $command->queryAll();
        //        foreach ($result as $item) {
        //            $bonusIn = BonusIn::findOne(['user_id' => $item['user_id'], 'order_id' => $item['order_id']]);
        //            if (!$bonusIn) {
        //                $bonusIn = new BonusIn();
        //                $bonusIn->user_id = $item['user_id'];
        //                $bonusIn->order_id = $item['order_id'];
        //                $bonusIn->bonus = floor($item['sum'] * 0.05);
        //                $bonusIn->save(false);
        //            } else {
        //                if ($bonusIn->bonus != floor($item['sum'] * 0.05)) {
        //                    $bonusIn->bonus = floor($item['sum'] * 0.05);
        //                    $bonusIn->save(false);
        //                }
        //            }
        //        }

        //        $users = User::find()->all();
        //        foreach ($users as $user) {
        //            $bonus = $user->calcBonus($user->id);
        //            if ($user->bonus != $bonus) {
        //                $user->bonus = $bonus;
        //                $user->save(false);
        //            } else {
        //                $k = 1;
        //            }
        //        }

        //        $request = Yii::$app->request;
        //        $post = $request->post();
        //        Yii::info([
        //            'isPost' => $request->isPost,
        //            'post' => $post,
        //        ], 'api');
    }
}
