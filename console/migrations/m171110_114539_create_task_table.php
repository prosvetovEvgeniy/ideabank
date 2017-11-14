<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m171110_114539_create_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'project_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(0),
            'visibility_area' => $this->integer()->defaultValue(0),
            'parent_id' => $this->integer()->defaultValue(null),
            'planned_end_at' => $this->integer()->defaultValue(null),
            'end_at' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'deleted' => $this->boolean()->defaultValue(false)
        ]);

        $this->addForeignKey('task_users_id_fk', 'task', 'author_id', 'users', 'id');
        $this->addForeignKey('task_project_id_fk', 'task', 'project_id', 'project', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('task_users_id_fk', 'task');
        $this->dropForeignKey('task_project_id_fk', 'task');

        $this->dropTable('task');
    }
}
