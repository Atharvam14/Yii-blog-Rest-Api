<?php

namespace console\controllers;

use yii;
use yii\console\controller;

class RbacController extends Controller
{
 public function actionInit()
 {
   $auth = Yii::$app->authManager;

   //create Permission
    $createComment = $auth->createPermission('createComment');
    $auth->add($createComment);
    
    $updateComment = $auth->createPermission('updateComment');
    $auth->add($updateComment);

    $deleteComment = $auth->createPermission('deleteComment');
    $auth->add($deleteComment);

    //create Role
    $user = $auth->createRole('user');
    $auth->add($user);

    $admin = $auth->createRole('admin');
    $auth->add($admin);

    //Assigning Permission to rule
    $auth->addChild($user, $createComment);

    $auth->addChild($admin, $createComment);
    $auth->addChild($admin, $updateComment);
    $auth->addChild($admin, $deleteComment);
    
    $auth->assign($admin, 1);

 }

}