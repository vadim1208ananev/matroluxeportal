<?php

namespace console\controllers;

use common\rbac\AuthorRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //permissions
        $createOrder = $auth->createPermission('createOrder');
        $createOrder->description = 'Create order';
        $auth->add($createOrder);

        $viewOrder = $auth->createPermission('viewOrder');
        $viewOrder->description = 'View order';
        $auth->add($viewOrder);

        $rule = new AuthorRule();
        $auth->add($rule);
        $viewOwnOrder = $auth->createPermission('viewOwnOrder');
        $viewOwnOrder->description = 'View own order';
        $viewOwnOrder->ruleName = $rule->name;
        $auth->add($viewOwnOrder);
        $auth->addChild($viewOwnOrder, $viewOrder);

        //roles
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createOrder);
        $auth->addChild($author, $viewOwnOrder);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $viewOrder);
        $auth->addChild($admin, $author);

        //assigns
        $auth->assign($admin, 1);
        $auth->assign($author, 3);
        $auth->assign($author, 4);
    }
}