<?php

use yii\db\Migration;

class m170712_150021_alter_sort_column_to_auto_service_works_assignment_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%auto_services_works_assignment}}', 'sort', $this->integer()->unsigned()->notNull());
        $this->dropColumn('{{%auto_services}}', 'sort');
    }

    public function down()
    {
        $this->dropColumn('{{%auto_services_works_assignment}}', 'sort');
        $this->addColumn('{{%auto_services}}', 'sort', $this->smallInteger()->unsigned()->notNull());
    }
}
