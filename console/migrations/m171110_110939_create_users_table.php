<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m171110_110939_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password' => $this->string(64)->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string()->defaultValue(null),
            'first_name' => $this->string()->defaultValue(null),
            'second_name' => $this->string()->defaultValue(null),
            'last_name' => $this->string()->defaultValue(null),
            'avatar' => $this->string()->defaultValue(null),
            'auth_key' => $this->string()->defaultValue(null),
            'password_reset_token' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'deleted' => $this->boolean()->defaultValue(false)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
