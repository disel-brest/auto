<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ad_part`.
 */
class m170408_084328_create_ad_part_table extends Migration
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

        $this->createTable('{{%ad_part}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'fuel_id' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'engine_volume' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'year' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'body_style' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'category_id' => $this->smallInteger()->notNull(),
            'name' => $this->string(100)->notNull(),
            'description' => $this->string()->notNull(),
            'photo' => $this->string(38)->notNull(),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-ad_part-user_id', '{{%ad_part}}', 'user_id');
        $this->createIndex('idx-ad_part-brand_id', '{{%ad_part}}', 'brand_id');
        $this->createIndex('idx-ad_part-model_id', '{{%ad_part}}', 'model_id');
        $this->addForeignKey('fk-ad_part-user_id-user-id', '{{%ad_part}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_part-brand_id-auto_brand-id', '{{%ad_part}}', 'brand_id', '{{%auto_brand}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_part-model_id-auto_model-id', '{{%ad_part}}', 'model_id', '{{%auto_model}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%ad_part}}');
    }
}
