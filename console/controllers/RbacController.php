<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;


class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');

        $auth->add($admin);
        $auth->add($user);

        $viewAdmin = $auth->createPermission('viewAdmin');
        $viewAdmin->description = 'Просмотр админки';

        $auth->add($viewAdmin);

        $auth->addChild($admin, $viewAdmin);

        $auth->assign($admin, 1);

        $auth->assign($user, 2);
    }
}