<?php

namespace common\models\builders;

use common\models\activerecords\TaskFile;
use common\models\entities\TaskFileEntity;

/**
 * Class TaskFileEntityBuilder
 * @package common\models\builders
 */
class TaskFileEntityBuilder
{
    /**
     * @return TaskFileEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param TaskFile $model
     * @param TaskFileEntity $taskFile
     */
    public function assignProperties(TaskFile &$model, TaskFileEntity &$taskFile)
    {
        $model->task_id = $taskFile->getTaskId();
        $model->hash_name = $taskFile->getHashName();
        $model->original_name = $taskFile->getOriginalName();
    }

    /**
     * @param TaskFile $model
     * @return TaskFileEntity
     */
    public function buildEntity(TaskFile $model)
    {
        $task = null;

        if ($model->isRelationPopulated('task')) {
            $task = ($model->task) ? TaskEntityBuilder::instance()->buildEntity($model->task) : null;
        }

        return new TaskFileEntity(
            $model->task_id, 
            $model->hash_name, 
            $model->original_name, 
            $model->id,
            $model->created_at, 
            $model->deleted,
            $task
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param TaskFile[] $models
     * @return TaskFileEntity[]
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