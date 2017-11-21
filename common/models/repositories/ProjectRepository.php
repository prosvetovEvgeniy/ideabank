<?php

namespace common\models\repositories;


use common\models\activerecords\Project;
use common\models\entities\ProjectEntity;
use yii\db\Exception;
use Yii;

class ProjectRepository
{
    /**
     * @return ProjectRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * @param $id
     * @return ProjectEntity
     * @throws Exception
     */
    public function get($id)
    {
        $model = Project::findOne(['id' => $id]);

        if(!$model)
        {
            throw new Exception('Project with id = ' . $id . ' does not exists');
        }

        return $this->buildEntity($model);
    }

    /**
     * @param ProjectEntity $project
     * @throws Exception
     */
    public function add($project)
    {
        $model = new Project();

        $model->name = $project->getName();
        $model->company_id = $project->getCompanyId();

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save project with name = ' . $project->getName());
        }
    }

    /**
     * @param ProjectEntity $project
     * @throws Exception
     */
    public function update($project)
    {
        $model = Project::findOne(['id' => $project->getId()]);

        $model->name = $project->getName();
        $model->company_id = $project->getCompanyId();
        $model->default_visibility_area = $project->getVisibilityArea();

        if(!$model->update())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update company with id = ' . $project->getId());
        }
    }

    /**
     * @param ProjectEntity $project
     * @throws Exception
     */
    public function delete($project)
    {
        $model = Project::findOne(['id' => $project->getId()]);

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete company with id = ' . $project->getId());
        }
    }

    /**
     * @param integer $id
     * @return ProjectEntity[]
     */
    public function getByCompanyId($id)
    {
        /** @var Project[] $models */
        $models = Project::find()->where(['company_id' => $id])->all();

        if(!$models)
        {
            throw new Exception('Cannot find project with company_id = ' . $id);
        }

        $entities = [];

        foreach ($models as $model)
        {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }

    /**
     * @param Project $model
     * @return ProjectEntity
     */
    protected function buildEntity($model)
    {
        return new ProjectEntity($model->name,$model->company_id, $model->id, $model->default_visibility_area,
                                 $model->created_at, $model->updated_at);
    }
}