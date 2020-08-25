<?php

use yii\db\Migration;

class m170802_094529_alter_complaint_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%complaint}}', 'message', $this->text()->notNull()->after('user_id'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%complaint}}', 'message');
    }
}
