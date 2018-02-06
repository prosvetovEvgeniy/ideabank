<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notice`.
 */
class m171114_051233_create_notice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('notice', [
            'id' => $this->primaryKey(),
            'recipient_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->defaultValue(null),
            'content' => $this->text()->notNull(),
            'link' => $this->text()->notNull(),
            'task_id' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer(),
        ]);

        $this->addForeignKey('notice_recipient_id_fk','notice','recipient_id', 'users','id');
        $this->addForeignKey('notice_sender_id_fk','notice','sender_id', 'users','id');

        $this->createIndex('notice_recipient_id_index', 'notice', 'recipient_id');
        $this->createIndex('notice_sender_id_index', 'notice', 'sender_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('notice_recipient_id_fk', 'notice');
        $this->dropForeignKey('notice_sender_id_fk', 'notice');

        $this->dropIndex('notice_recipient_id_index', 'notice');
        $this->dropIndex('notice_sender_id_index', 'notice');

        $this->dropTable('notice');
    }
}
