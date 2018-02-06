<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_notice`.
 */
class m180206_070843_create_task_notice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task_notice', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'notice_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('task_notice_task_id_fk', 'task_notice','task_id', 'task','id');
        $this->addForeignKey('task_notice_notice_id_fk', 'task_notice', 'notice_id','notice','id');

        $this->createIndex('task_notice_task_id_index', 'task_notice', 'task_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('task_notice_task_id_index', 'task_notice');

        $this->dropForeignKey('task_notice_notice_id_fk', 'task_notice');
        $this->dropForeignKey('task_notice_task_id_fk', 'task_notice');

        $this->dropTable('task_notice');
    }
}
