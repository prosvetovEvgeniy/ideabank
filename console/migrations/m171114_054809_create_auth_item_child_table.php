<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_item_child`.
 */
class m171114_054809_create_auth_item_child_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
        ]);

        $this->addForeignKey('auth_item_child_parent_fkey','auth_item_child', 'parent', 'auth_item',
            'name', 'CASCADE', 'CASCADE');

        $this->addForeignKey('auth_item_child_child_fkey', 'auth_item_child', 'child','auth_item',
            'name', 'CASCADE','CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('auth_item_child_parent_fkey', 'auth_item_child');
        $this->dropForeignKey('auth_item_child_child_fkey', 'auth_item_child');

        $this->dropTable('auth_item_child');
    }
}
