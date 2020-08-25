<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ad_car`.
 */
class m170424_182359_create_ad_car_table extends Migration
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

        $this->createTable('{{%ad_car}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'year' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'odometer' => $this->integer()->notNull()->defaultValue(0),
            'body_style' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'fuel_id' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'engine_volume' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'transmission' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'drivetrain' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'color' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'photo' => $this->text()->notNull(),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'bargain' => $this->boolean()->notNull()->defaultValue(false),
            'change' => $this->boolean()->notNull()->defaultValue(false),
            'law_firm' => $this->boolean()->notNull()->defaultValue(false),
            'description' => $this->text()->notNull(),

            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-ad_car-user_id', '{{%ad_car}}', 'user_id');
        $this->createIndex('idx-ad_car-brand_id', '{{%ad_car}}', 'brand_id');
        $this->createIndex('idx-ad_car-model_id', '{{%ad_car}}', 'model_id');
        $this->addForeignKey('fk-ad_car-user_id-user-id', '{{%ad_car}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_car-brand_id-auto_brand-id', '{{%ad_car}}', 'brand_id', '{{%auto_brand}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_car-model_id-auto_model-id', '{{%ad_car}}', 'model_id', '{{%auto_model}}', 'id', 'CASCADE', 'CASCADE');

        // Таблица опций
        $this->createTable('{{%car_options}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ], $tableOptions);

        $this->createTable('{{%car_options_assignment}}', [
            'ad_car_id' => $this->integer()->notNull(),
            'option_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-car_options_assignment', '{{%car_options_assignment}}', ['ad_car_id', 'option_id']);
        $this->addForeignKey('fk-c_o_a-ad_car_id-ad_car-id', '{{%car_options_assignment}}', 'ad_car_id', '{{%ad_car}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-c_o_a-option_id-car_options-id', '{{%car_options_assignment}}', 'option_id', '{{%car_options}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%car_options_assignment}}');
        $this->dropTable('{{%car_options}}');
        $this->dropTable('{{%ad_car}}');
    }
}
