<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_item`.
 */
class m171114_053705_create_auth_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth_item', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ]);

        $this->addForeignKey('auth_item_rule_name_fkey','auth_item','name','auth_rule','name','SET NULL','CASCADE');
        $this->createIndex('idx-auth_item-type', 'auth_item', 'type');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('auth_item_rule_name_fkey', 'auth_item');
        $this->dropIndex('idx-auth_item-type', 'auth_item');

        $this->dropTable('auth_item');
    }
}
