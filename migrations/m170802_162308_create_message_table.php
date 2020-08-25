<?php

use yii\db\Migration;

/**
 * Handles the creation of table `message`.
 */
class m170802_162308_create_message_table extends Migration
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

        $this->createTable('{{%dialogs}}', [
            'id' => $this->primaryKey(),
            'user_one' => $this->integer()->notNull(),
            'user_two' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-dialogs-user_one', '{{%dialogs}}', 'user_one');
        $this->createIndex('idx-dialogs-user_two', '{{%dialogs}}', 'user_two');
        $this->createIndex('idx-dialogs-users', '{{%dialogs}}', ['user_one', 'user_two']);
        $this->addForeignKey('fk-dialogs-user_one-user-id', '{{%dialogs}}', 'user_one', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-dialogs-user_two-user-id', '{{%dialogs}}', 'user_two', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'dialog_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'subject' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'is_new' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-messages-dialog_id', '{{%messages}}', 'dialog_id');
        $this->createIndex('idx-messages-user_id', '{{%messages}}', 'user_id');
        $this->addForeignKey('fk-messages-dialog_id-dialogs-id', '{{%messages}}', 'dialog_id', '{{%dialogs}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-messages-user_id-user-id', '{{%messages}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%messages}}');
        $this->dropTable('{{%dialogs}}');
    }
}
