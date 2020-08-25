<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auto_service`.
 */
class m170630_091903_create_auto_service_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auto_services}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'sub_text' => $this->string()->notNull(),
            'legal_name' => $this->string()->notNull(),
            'city_id' => $this->integer()->notNull(),
            'street' => $this->string()->notNull(),
            'unp' => $this->string()->notNull(),
            'year' => $this->smallInteger()->unsigned(),
            'phones' => $this->text()->notNull(),
            'site' => $this->string()->notNull(),
            'work_schedule' => $this->text()->notNull(),
            'about' => $this->text()->notNull(),
            'info' => $this->string()->notNull(),
            'background' => $this->string(40)->notNull(),
            'photos' => $this->text()->notNull(),
            'lat' => $this->decimal(9, 6)->notNull(),
            'lng' => $this->decimal(9, 6)->notNull(),
            'sort' => $this->smallInteger()->unsigned()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-auto_services-city_id', '{{%auto_services}}', 'city_id');
        $this->createIndex('idx-auto_services-lat', '{{%auto_services}}', 'lat');
        $this->createIndex('idx-auto_services-lng', '{{%auto_services}}', 'lng');
        $this->addForeignKey('fk-auto_services-city_id-city-id', '{{%auto_services}}', 'city_id', '{{%city}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%auto_service_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'photo' => $this->string(40)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%auto_service_works}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-auto_service_works-category_id', '{{%auto_service_works}}', 'category_id');
        $this->addForeignKey('fk-auto_service_works-category_id-auto_service_categories-id', '{{%auto_service_works}}', 'category_id', '{{%auto_service_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%auto_services_works_assignment}}', [
            'service_id' => $this->integer()->notNull(),
            'work_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-auto_services_works_assignment', '{{%auto_services_works_assignment}}', ['service_id', 'work_id']);
        $this->createIndex('idx-auto_services_works_assignment-service_id', '{{%auto_services_works_assignment}}', 'service_id');
        $this->createIndex('idx-auto_services_works_assignment-work_id', '{{%auto_services_works_assignment}}', 'work_id');
        $this->addForeignKey('fk-auto_services_works_assignment-service_id-auto_services-id', '{{%auto_services_works_assignment}}', 'service_id', '{{%auto_services}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auto_services_works_assignment-work_id-auto_service_works-id', '{{%auto_services_works_assignment}}', 'work_id', '{{%auto_service_works}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%auto_services_works_assignment}}');
        $this->dropTable('{{%auto_service_works}}');
        $this->dropTable('{{%auto_service_categories}}');
        $this->dropTable('{{%auto_service}}');
    }
}
