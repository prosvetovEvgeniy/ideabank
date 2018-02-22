<?php

namespace common\models\builders;

use common\models\activerecords\Project;
use common\models\entities\ProjectEntity;

/**
 * Class ProjectEntityBuilder
 * @package common\models\builders
 */
class ProjectEntityBuilder
{
    /**
     * @return ProjectEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Project $model
     * @param ProjectEntity $project
     */
    public function assignProperties(Project &$model, ProjectEntity &$project)
    {
        $model->name = $project->getName();
        $model->company_id = $project->getCompanyId();
        $model->description = $project->getDescription();
        $model->default_visibility_area = $project->getDefaultVisibilityArea();
    }

    /**
     * @param Project $model
     * @return ProjectEntity
     */
    public function buildEntity(Project $model)
    {
        $company = null;
        $tasks = null;
        $participants = null;

        if ($model->isRelationPopulated('company')) {
            $company = ($model->company) ? CompanyEntityBuilder::instance()->buildEntity($model->company) : null;
        }

        if ($model->isRelationPopulated('tasks')) {
            $tasks = ($model->tasks) ? TaskEntityBuilder::instance()->buildEntities($model->tasks) : null;
        }

        if ($model->isRelationPopulated('participants')) {
            $participants = ($model->participants) ? ParticipantEntityBuilder::instance()->buildEntities($model->participants) : null;
        }

        return new ProjectEntity(
            $model->name,
            $model->company_id, 
            $model->description,
            $model->default_visibility_area, 
            $model->id,
            $model->created_at,
            $model->updated_at, 
            $model->deleted,
            $company,
            $tasks,
            $participants
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Project[] $models
     * @return ProjectEntity[]
     */
    public function buildEntities(array $models)
    {
        if (!$models) {
            return [];
        }

        $entities = [];

        foreach ($models as $model) {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }
}