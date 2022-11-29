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


        $createSpec = $auth->createPermission('createSpec');
        $createSpec->description = 'Create spec';
        $auth->add($createSpec);

        $viewSpec = $auth->createPermission('viewSpec');
        $viewSpec->description = 'View spec';
        $auth->add($viewSpec);

//        $rule = new AuthorRule();
//        $auth->add($rule);
        $viewOwnSpec = $auth->createPermission('viewOwnSpec');
        $viewOwnSpec->description = 'View own spec';
        $viewOwnSpec->ruleName = $rule->name;
        $auth->add($viewOwnSpec);
        $auth->addChild($viewOwnSpec, $viewSpec);

        $viewBackend = $auth->createPermission('viewBackend');
        $viewBackend->description = 'View backend';
        $auth->add($viewBackend);

        $viewMap = $auth->createPermission('viewMap');
        $viewMap->description = 'View map';
        $auth->add($viewMap);

        //roles
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createOrder);
        $auth->addChild($author, $viewOwnOrder);
        $auth->addChild($author, $createSpec);
        $auth->addChild($author, $viewOwnSpec);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $viewOrder);
        $auth->addChild($admin, $viewSpec);
        $auth->addChild($admin, $author);

        $backend = $auth->createRole('backend');
        $auth->add($backend);
        $auth->addChild($backend, $viewBackend);
        $auth->addChild($admin, $backend);

        $map = $auth->createRole('map');
        $auth->add($map);
        $auth->addChild($map, $viewMap);
        $auth->addChild($admin, $map);

        //assigns
        $auth->assign($admin, 1);
        $auth->assign($backend, 142);
        $auth->assign($backend, 144);
        $auth->assign($backend, 304);
        $auth->assign($map, 142);
        $auth->assign($map, 165);
    }
}