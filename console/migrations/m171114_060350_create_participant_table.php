<?php

use yii\db\Migration;

/**
 * Handles the creation of table `participant`.
 */
class m171114_060350_create_participant_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('participant', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'company_id' => $this->integer(),
            'approved' => $this->boolean()->defaultValue(false),
            'approved_at' => $this->integer(),
            'blocked' => $this->boolean()->defaultValue(false),
            'blocked_at' => $this->integer(),
            'created_at' => $this->integer(),

        ]);

        $this->addForeignKey('participant_users_id','participant','user_id','users','id');
        $this->addForeignKey('participant_company_id','participant','company_id','company','id');

        $this->createIndex('participant_user_id_index','participant','user_id');
        $this->createIndex('participant_company_id_index','participant','company_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('participant_user_id_index','participant');
        $this->dropIndex('participant_company_id_index','participant');

        $this->dropForeignKey('participant_users_id', 'participant');
        $this->dropForeignKey('participant_company_id', 'participant');

        $this->dropTable('participant');
    }
}
