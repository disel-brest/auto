<?php

use yii\db\Migration;

class m170505_125823_create_tires_brand_models_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tire_brand}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%tire_model}}', [
            'id' => $this->primaryKey(),
            'brand_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-tire_model-brand_id', '{{%tire_model}}', 'brand_id');
        $this->addForeignKey('fk-tire_model-brand_id-tire_brand-id', '{{%tire_model}}', 'brand_id', '{{%tire_brand}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%tire_model}}');
        $this->dropTable('{{%tire_brand}}');
    }
}
