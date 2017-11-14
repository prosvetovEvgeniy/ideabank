<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m171114_040101_create_message_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('message', [
            'id' => $this->primaryKey(),
            'self_id' => $this->integer()->notNull(),
            'companion_id' => $this->integer()->notNull(),
            'content' => $this->text(),
            'is_sender' => $this->boolean()->notNull(),
            'created_at' => $this->integer(),
            'deleted' => $this->boolean()->defaultValue(false)
        ]);

        $this->addForeignKey('message_users_self_id_fk', 'message', 'self_id', 'users', 'id');
        $this->addForeignKey('message_users_companion_id_fk', 'message', 'companion_id', 'users', 'id');

        $this->createIndex('message_self_id_index', 'message','self_id');
        $this->createIndex('message_companion_id_index','message', 'companion_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('message_self_id_index','message');
        $this->dropIndex('message_companion_id_index','message');

        $this->dropForeignKey('message_users_self_id_fk', 'message');
        $this->dropForeignKey('message_users_companion_id_fk', 'message');

        $this->dropTable('message');
    }
}
