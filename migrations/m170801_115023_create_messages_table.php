<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m170801_115023_create_messages_table extends Migration
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

        $this->createTable('{{%ad_dialogs}}', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'ad_type' => $this->smallInteger()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-ad_dialogs-ad_id', '{{%ad_dialogs}}', 'ad_id');
        $this->createIndex('idx-ad_dialogs-user_id', '{{%ad_dialogs}}', 'user_id');
        $this->addForeignKey('fk-ad_dialogs-user_id-user-id', '{{%ad_dialogs}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%ad_messages}}', [
            'id' => $this->primaryKey(),
            'dialog_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'subject' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'is_new' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-ad_messages-dialog_id', '{{%ad_messages}}', 'dialog_id');
        $this->createIndex('idx-ad_messages-user_id', '{{%ad_messages}}', 'user_id');
        $this->addForeignKey('fk-ad_messages-dialog_id-ad_dialogs-id', '{{%ad_messages}}', 'dialog_id', '{{%ad_dialogs}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ad_messages-user_id-user-id', '{{%ad_messages}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%ad_messages}}');
        $this->dropTable('{{%ad_dialogs}}');
    }
}
