<?php

namespace frontend\controllers;

use yii;
use frontend\resource\Comment;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;

class CommentController extends ActiveController
{
    public $modelClass = Comment::class;

    public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator']['only'] = ['create', 'update','delete'];
    $behaviors['authenticator']['authMethods'] = [
      HttpBearerAuth::class
    ];

     return $behaviors;

  }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
       
$postId = Yii::$app->request->get('postId', Yii::$app->request->get('post_id'));

$query = $this->modelClass::find();
if ($postId !== null) {
    $query->andWhere(['post_id' => $postId]);
}

return new ActiveDataProvider(['query' => $query]);
        
    }
}
 
