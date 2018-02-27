<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_log`.
 */
class m180227_024757_create_auth_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth_log', [
            'id' => $this->primaryKey(),
            'changer_id' => $this->integer()->notNull(),
            'changeable_id' => $this->integer()->notNull(),
            'role_name' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('auth_log_changer_id', 'auth_log', 'changer_id', 'participant', 'id');
        $this->addForeignKey('auth_log_changeable_id', 'auth_log', 'changeable_id', 'participant', 'id');
        $this->addForeignKey('auth_log_role_name', 'auth_log', 'role_name', 'auth_item', 'name');

        $this->createIndex('auth_log_changer_id_index', 'auth_log', 'changer_id');
        $this->createIndex('auth_log_changeable_id_index', 'auth_log', 'changeable_id');
        $this->createIndex('auth_log_role_name_index', 'auth_log', 'role_name');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('auth_log_role_name_index', 'auth_log');
        $this->dropIndex('auth_log_changeable_id_index', 'auth_log');
        $this->dropIndex('auth_log_changer_id_index', 'auth_log');

        $this->dropForeignKey('auth_log_role_name', 'auth_log');
        $this->dropForeignKey('auth_log_changeable_id', 'auth_log');
        $this->dropForeignKey('auth_log_changer_id', 'auth_log');

        $this->dropTable('auth_log');
    }
}
