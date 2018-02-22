<?php

namespace common\models\builders;

use common\models\activerecords\Task;
use common\models\entities\TaskEntity;
use yii\db\ActiveRecord;

class TaskEntityBuilder
{
    /**
     * @return TaskEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Task $model
     * @param TaskEntity $task
     */
    public function assignProperties(Task &$model, TaskEntity &$task)
    {
        $model->title = $task->getTitle();
        $model->content = $task->getContent();
        $model->author_id = $task->getAuthorId();
        $model->project_id = $task->getProjectId();
        $model->status = $task->getStatus();
        $model->visibility_area = $task->getVisibilityArea();
        $model->parent_id = $task->getParentId();
        $model->planned_end_at = $task->getPlannedEndAt();
        $model->end_at = $task->getEndAt();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param Task $model
     * @return TaskEntity
     */
    public function buildEntity(Task $model)
    {
        $project = null;
        $parent = null;
        $author = null;

        if ($model->isRelationPopulated('project')) {
            $project = ($model->project) ? ProjectEntityBuilder::instance()->buildEntity($model->project) : null;
        }

        if ($model->isRelationPopulated('parent')) {
            $parent = ($model->parent) ? TaskEntityBuilder::instance()->buildEntity($model->parent) : null;
        }

        if ($model->isRelationPopulated('author')) {
            $author = ($model->author) ? UserEntityBuilder::instance()->buildEntity($model->author) : null;
        }

        return new TaskEntity(
            $model->title,
            $model->content,
            $model->author_id,
            $model->project_id,
            $model->status,
            $model->visibility_area,
            $model->parent_id,
            $model->planned_end_at,
            $model->end_at, $model->id,
            $model->created_at,
            $model->updated_at,
            $model->deleted,
            $project,
            $parent,
            $author
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Task[] $models
     * @return TaskEntity[]
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