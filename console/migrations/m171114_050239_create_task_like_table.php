<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_like`.
 */
class m171114_050239_create_task_like_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task_like', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'liked' => $this->boolean(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('task_like_task_id_fk', 'task_like', 'task_id', 'task', 'id');
        $this->addForeignKey('task_like_users_id_fk', 'task_like', 'user_id', 'users', 'id');

        $this->createIndex('task_like_task_id_index', 'task_like','task_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('task_like_task_id_index', 'task_like');

        $this->dropForeignKey('task_like_task_id_fk', 'task_like');
        $this->dropForeignKey('task_like_users_id_fk', 'task_like');

        $this->dropTable('task_like');
    }
}
