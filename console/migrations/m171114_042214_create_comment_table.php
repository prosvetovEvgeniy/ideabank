<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m171114_042214_create_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'content' => $this->string(2000)->notNull(),
            'parent_id' => $this->integer()->defaultValue(null),
            'private' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted' => $this->boolean()->defaultValue(false)
        ]);

        $this->addForeignKey('comment_task_id_fk', 'comment', 'task_id','task','id');
        $this->addForeignKey('comment_users_id_fk', 'comment', 'sender_id','users','id');
        $this->addForeignKey('comment_parent_id_fk', 'comment', 'parent_id','comment','id');

        $this->createIndex('comment_task_id_index', 'comment', 'task_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('comment_task_id_index', 'comment');

        $this->dropForeignKey('comment_task_id_fk', 'comment');
        $this->dropForeignKey('comment_users_id_fk', 'comment');
        $this->dropForeignKey('comment_parent_id_fk', 'comment');

        $this->dropTable('comment');
    }
}
