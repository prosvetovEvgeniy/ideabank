<?php

namespace common\models\builders;

use common\models\activerecords\TaskLike;
use common\models\entities\TaskLikeEntity;

class TaskLikeEntityBuilder
{
    /**
     * @return TaskLikeEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param TaskLike $model
     * @param TaskLikeEntity $taskLike
     */
    public function assignProperties(TaskLike &$model, TaskLikeEntity &$taskLike)
    {
        $model->task_id = $taskLike->getTaskId();
        $model->user_id = $taskLike->getUserId();
        $model->liked = $taskLike->getLiked();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param TaskLike $model
     * @return TaskLikeEntity
     */
    public function buildEntity(TaskLike $model)
    {
        return new TaskLikeEntity(
            $model->task_id, 
            $model->user_id, 
            $model->liked,
            $model->id,
            $model->created_at, 
            $model->updated_at
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param TaskLike[] $models
     * @return TaskLikeEntity[]
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