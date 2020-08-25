<?php

use yii\db\Migration;

class m170601_140903_alter_user_table extends Migration
{
    public function up()
    {
        //$this->alterColumn('{{%user}}', 'username', $this->string(25)->null());
        //$this->alterColumn('{{%user}}', 'email', $this->string()->null());
        $this->dropIndex('username', '{{%user}}');
        $this->dropIndex('email', '{{%user}}');
    }

    public function down()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string(25)->notNull()->unique());
        $this->alterColumn('{{%user}}', 'email', $this->string()->notNull()->unique());
    }
}
