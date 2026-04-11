<?php

namespace frontend\controllers;

use yii\filters\auth\HttpBearerAuth;
use frontend\resource\Post;

class PostController extends ActiveController
{
  public $modelClass = Post::class;

 public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator']['only'] = ['create', 'update','delete'];
    $behaviors['authenticator']['authMethods'] = [
      HttpBearerAuth::class
    ];

     return $behaviors;

  }
  
}
