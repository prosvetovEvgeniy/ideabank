<?php

namespace common\models\builders;

use common\models\activerecords\Task;
use common\models\entities\TaskEntity;
use yii\helpers\Html;

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
        $model->title = Html::encode($task->getTitle());
        $model->content = Html::encode($task->getContent());
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
        return new TaskEntity($model->title, $model->content, $model->author_id, $model->project_id,
                              $model->status, $model->visibility_area, $model->parent_id,
                              $model->planned_end_at, $model->end_at, $model->id, $model->created_at,
                              $model->updated_at, $model->deleted);
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