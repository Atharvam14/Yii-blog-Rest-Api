<?php

namespace frontend\resource;

class Post extends \common\models\Post
{
       public function fields()
   {
      return ['id','title','body',];
   }
 public function extraFields()
 {
    return['comments', 'createdBy'];
 }
/**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CommentQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
} 
