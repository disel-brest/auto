<?php

use yii\db\Migration;

class m170319_093858_add_fields_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'phone_operator', $this->string(32)->notNull()->after('email'));
        $this->addColumn('{{%user}}', 'phone', $this->string(32)->notNull()->after('email'));
        $this->addColumn('{{%user}}', 'call_time', $this->string()->notNull()->after('phone_operator'));
        $this->addColumn('{{%user}}', 'avatar', $this->string(21)->notNull()->after('call_time'));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'phone_operator');
        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'call_time');
        $this->dropColumn('{{%user}}', 'avatar');
    }
}
