<?php

use yii\db\Migration;

/**
 * Handles the creation of table `complaint`.
 */
class m170429_094129_create_complaint_table extends Migration
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

        $this->createTable('{{%complaint}}', [
            'id' => $this->primaryKey(),
            'ad_type' => $this->smallInteger()->notNull(),
            'ad_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk-complaint-user_id-user-id', '{{%complaint}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%complaint}}');
    }
}
