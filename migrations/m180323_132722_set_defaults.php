<?php

use yii\db\Migration;

class m180323_132722_set_defaults extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'email_confirm_token', $this->string()->notNull()->defaultValue(''));
        $this->alterColumn('{{%user}}', 'password_reset_token', $this->string()->notNull()->defaultValue(''));
        $this->alterColumn('{{%user}}', 'avatar', $this->string(21)->notNull()->defaultValue(''));

    }

    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'email_confirm_token', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'password_reset_token', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'avatar', $this->string(21)->notNull());
    }
}
