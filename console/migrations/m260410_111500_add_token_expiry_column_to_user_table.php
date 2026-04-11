<?php

use yii\db\Migration;

class m260410_111500_add_token_expiry_column_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'token_expiry', $this->integer()->null()->after('access_token'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'token_expiry');
    }
}
