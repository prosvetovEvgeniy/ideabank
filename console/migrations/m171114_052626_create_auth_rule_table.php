<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_rule`.
 */
class m171114_052626_create_auth_rule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth_rule', [
            'name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addPrimaryKey('auth_rule_pkey','auth_rule','name');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('auth_rule');
    }
}
