<?php

namespace common\models\builders;

use common\models\activerecords\Project;
use common\models\entities\ProjectEntity;
use yii\helpers\Html;

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
        $model->description = Html::encode($project->getDescription());
        $model->default_visibility_area = $project->getDefaultVisibilityArea();
    }

    /**
     * @param Project $model
     * @return ProjectEntity
     */
    public function buildEntity(Project $model)
    {
        return new ProjectEntity($model->name,$model->company_id, $model->description,
                                 $model->default_visibility_area, $model->id, $model->created_at,
                                 $model->updated_at, $model->deleted);
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