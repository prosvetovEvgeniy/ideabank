<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment_like`.
 */
class m171114_045146_create_comment_like_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment_like', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'liked' => $this->boolean(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('comment_like_comment_id_fk', 'comment_like', 'comment_id', 'comment', 'id');
        $this->addForeignKey('comment_like_users_id_fk', 'comment_like', 'user_id', 'users', 'id');

        $this->createIndex('comment_like_comment_id_index', 'comment_like','comment_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('comment_like_comment_id_index', 'comment_like');

        $this->dropForeignKey('comment_like_comment_id_fk', 'comment_like');
        $this->dropForeignKey('comment_like_users_id_fk', 'comment_like');

        $this->dropTable('comment_like');
    }
}
