<?php

use yii\db\Migration;

class m171210_194811_change_ad_tire_table_model_id_column_to_null extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk-ad_tire-model_id-tire_model-id', '{{%ad_tire}}');
        $this->alterColumn('{{%ad_tire}}', 'model_id', $this->integer()->null());
        $this->addForeignKey('fk-ad_tire-model_id-tire_model-id', '{{%ad_tire}}', 'model_id', '{{%tire_model}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-ad_tire-model_id-tire_model-id', '{{%ad_tire}}');
        $this->alterColumn('{{%ad_tire}}', 'model_id', $this->integer()->notNull());
        $this->addForeignKey('fk-ad_tire-model_id-tire_model-id', '{{%ad_tire}}', 'model_id', '{{%tire_model}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
