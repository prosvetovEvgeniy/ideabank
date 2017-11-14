<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notice`.
 */
class m171114_051233_create_notice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('notice', [
            'id' => $this->primaryKey(),
            'recipient_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer(),
            'viewed' => $this->boolean()->defaultValue(false)
        ]);

        $this->addForeignKey('notice_users_id_fk','notice','recipient_id', 'users','id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('notice_users_id_fk', 'notice');

        $this->dropTable('notice');
    }
}
