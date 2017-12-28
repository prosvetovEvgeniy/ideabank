<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_file`.
 */
class m171226_060916_create_task_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task_file', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'hash_name' => $this->string()->notNull(),
            'original_name' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'deleted' => $this->boolean()->defaultValue(false)
        ]);

        $this->addForeignKey('task_file_task_id_fk', 'task_file', 'task_id','task', 'id');

        $this->createIndex('task_file_task_id_index', 'task_file', 'task_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('task_file_task_id_fk', 'task_file');
        $this->dropIndex('task_file_task_id_index', 'task_file');
        $this->dropTable('task_file');
    }
}
