<?php

use yii\db\Migration;

class m170721_104720_add_status_to_auto_service_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%auto_services}}', 'status', $this->smallInteger()->notNull()->after('lng'));
        $this->addColumn('{{%auto_services}}', 'views', $this->integer()->notNull()->after('status'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%auto_services}}', 'status');
        $this->dropColumn('{{%auto_services}}', 'views');
    }
}
