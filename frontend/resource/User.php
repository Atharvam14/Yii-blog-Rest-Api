<?php

namespace frontend\resource;

class User extends \common\models\User
{
    public function fields()
    {
        return ['id', 'username', 'email'];
    }
}
