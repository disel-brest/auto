<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auto_brand`.
 */
class m170328_185931_create_auto_brand_table extends Migration
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

        $this->createTable('{{%auto_brand}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%auto_model}}', [
            'id' => $this->primaryKey(),
            'brand_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-auto_model-brand_id', '{{%auto_model}}', 'brand_id');
        $this->addForeignKey('fk-auto_model-brand_id-auto_brand-id', '{{%auto_model}}', 'brand_id', '{{%auto_brand}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auto_model}}');
        $this->dropTable('{{%auto_brand}}');
    }
}
