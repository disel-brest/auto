<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wheel`.
 */
class m170515_095511_create_wheel_table extends Migration
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

        $this->createTable('{{%ad_wheel}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'wheel_auto' => $this->smallInteger()->notNull(),
            'is_new' => $this->boolean()->notNull()->defaultValue(false),
            'wheel_type' => $this->smallInteger()->notNull(),
            'auto_brand_id' => $this->integer()->null(),
            'firm' => $this->string()->notNull(),
            'radius' => $this->smallInteger()->notNull(),
            'bolts' => $this->smallInteger()->notNull(),
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
        $this->createIndex('idx-ad_wheel-auto_brand_id', '{{%ad_wheel}}', 'auto_brand_id');
        $this->createIndex('idx-ad_wheel-bolts', '{{%ad_wheel}}', 'bolts');
        $this->createIndex('idx-ad_wheel-radius', '{{%ad_wheel}}', 'radius');
        $this->addForeignKey('fk-ad_wheel-user_id-user-id', '{{%ad_wheel}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_wheel-auto_brand_id-auto_brand-id', '{{%ad_wheel}}', 'auto_brand_id', '{{%auto_brand}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%ad_wheel}}');
    }
}
