<?php

namespace common\models\repositories;


use common\models\activerecords\Project;
use common\models\entities\ProjectEntity;
use yii\db\Exception;
use Yii;

class ProjectRepository
{
    /**
     * Возвращает экземпляр класса
     *
     * @return ProjectRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return ProjectEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = Project::findOne($condition);

        if(!$model)
        {
            throw new Exception('Project with ' . json_encode($condition) . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Project with ' . json_encode($condition) . ' already deleted');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return array ProjectEntity
     * @throws Exception
     */
    public function findAll(array $condition)
    {
        /** @var Project[] $models */
        $models = Project::findAll($condition);

        if(!$models)
        {
            return [];
        }

        $entities = [];

        foreach ($models as $model)
        {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }

    /**
     * Добавляет сущность в БД
     *
     * @param ProjectEntity $project
     * @return ProjectEntity
     * @throws Exception
     */
    public function add(ProjectEntity $project)
    {
        $model = new Project();

        $this->assignProperties($model, $project);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save project with name = ' . $project->getName());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param ProjectEntity $project
     * @return ProjectEntity
     * @throws Exception
     */
    public function update(ProjectEntity $project)
    {
        $model = Project::findOne(['id' => $project->getId()]);

        if(!$model)
        {
            throw new Exception('Project with id = ' . $project->getId() . ' does not exists');
        }

        $this->assignProperties($model, $project);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update project with id = ' . $project->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param ProjectEntity $project
     * @return ProjectEntity
     * @throws Exception
     */
    public function delete(ProjectEntity $project)
    {
        $model = Project::findOne(['id' => $project->getId()]);

        if(!$model)
        {
            throw new Exception('Project with id = ' . $project->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Project with id = ' . $project->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete project with id = ' . $project->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Project $model
     * @param ProjectEntity $project
     */
    protected function assignProperties(&$model, &$project)
    {
        $model->name = $project->getName();
        $model->company_id = $project->getCompanyId();
        $model->default_visibility_area = $project->getDefaultVisibilityArea();
    }

    /**
     * @param Project $model
     * @return ProjectEntity
     */
    protected function buildEntity(Project $model)
    {
        return new ProjectEntity($model->name,$model->company_id, $model->id, $model->default_visibility_area,
                                 $model->created_at, $model->updated_at, $model->deleted);
    }
}