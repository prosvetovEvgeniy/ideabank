<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project`.
 */
class m171110_114017_create_project_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('project', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'company_id' => $this->integer()->notNull(),
            'description' => $this->string()->defaultValue(null),
            'default_visibility_area' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'deleted' => $this->boolean()->defaultValue(false)
        ]);

        $this->addForeignKey('project_company_id_fk','project', 'company_id','company','id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('project_company_id_fk', 'project');
        $this->dropTable('project');
    }
}
