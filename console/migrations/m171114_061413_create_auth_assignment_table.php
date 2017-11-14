<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_assignment`.
 */
class m171114_061413_create_auth_assignment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth_assignment', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
        ]);

        $this->addForeignKey('auth_assignment_item_name_fkey', 'auth_assignment', 'item_name','auth_item','name','CASCADE','CASCADE');
        $this->addForeignKey('auth_assignment_participant_id_fk','auth_assignment','user_id','participant','id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('auth_assignment_item_name_fkey','auth_assignment');
        $this->dropForeignKey('auth_assignment_participant_id_fk','auth_assignment');

        $this->dropTable('auth_assignment');
    }
}
