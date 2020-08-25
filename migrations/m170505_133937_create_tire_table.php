<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tire`.
 */
class m170505_133937_create_tire_table extends Migration
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

        $this->createTable('{{%ad_tire}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'tire_type' => $this->smallInteger()->unsigned()->notNull(),
            'is_new' => $this->boolean()->notNull()->defaultValue(false),
            'season' => $this->smallInteger()->notNull(),
            'radius' => $this->smallInteger()->notNull(),
            'width' => $this->smallInteger()->notNull(),
            'aspect_ratio' => $this->smallInteger()->notNull(),
            'amount' => $this->smallInteger()->notNull(),
            'photo' => $this->text()->notNull(),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'bargain' => $this->boolean()->notNull()->defaultValue(false),
            'description' => $this->text()->notNull(),
            'condition' => $this->smallInteger()->notNull(),

            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'views' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'active_till' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-ad_tire-brand_id', '{{%ad_tire}}', 'brand_id');
        $this->createIndex('idx-ad_tire-model_id', '{{%ad_tire}}', 'model_id');
        $this->createIndex('idx-ad_tire-radius', '{{%ad_tire}}', 'radius');
        $this->addForeignKey('fk-ad_tire-user_id-user-id', '{{%ad_tire}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_tire-brand_id-tire_brand-id', '{{%ad_tire}}', 'brand_id', '{{%tire_brand}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-ad_tire-model_id-tire_model-id', '{{%ad_tire}}', 'model_id', '{{%tire_model}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%ad_tire}}');
    }
}
