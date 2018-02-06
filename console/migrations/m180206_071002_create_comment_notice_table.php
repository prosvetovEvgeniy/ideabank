<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment_notice`.
 */
class m180206_071002_create_comment_notice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment_notice', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer()->notNull(),
            'notice_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('comment_notice_comment_id', 'comment_notice', 'comment_id', 'comment', 'id');
        $this->addForeignKey('comment_notice_notice_id', 'comment_notice', 'notice_id', 'notice', 'id');

        $this->createIndex('comment_notice_comment_id_index', 'comment_notice', 'comment_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('comment_notice_comment_id', 'comment_notice');
        $this->dropForeignKey('comment_notice_notice_id', 'comment_notice');
        $this->dropIndex('comment_notice_comment_id_index', 'comment_notice');

        $this->dropTable('comment_notice');
    }
}
