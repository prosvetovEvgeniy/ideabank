<?php

namespace common\models\repositories;


use common\models\activerecords\TaskLike;
use common\models\entities\TaskLikeEntity;
use yii\db\Exception;
use Yii;

class TaskLikeRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return TaskLikeRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return TaskLikeEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = TaskLike::findOne($condition);

        if(!$model)
        {
            throw new Exception('task_like with ' . json_encode($condition) . ' does not exists');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return TaskLikeEntity[]
     */
    public function findAll(array $condition)
    {
        $models = TaskLike::findAll($condition);

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param TaskLikeEntity $taskLike
     * @return TaskLikeEntity
     * @throws Exception
     */
    public function add(TaskLikeEntity $taskLike)
    {
        $model = new TaskLike();

        $this->assignProperties($model, $taskLike);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save task_like with task_id = ' . $taskLike->getTaskId() .
                                ' and user_id = ' . $taskLike->getUserId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param TaskLikeEntity $taskLike
     * @return TaskLikeEntity
     * @throws Exception
     */
    public function update(TaskLikeEntity $taskLike)
    {
        $model = TaskLike::findOne(['id' => $taskLike->getId()]);

        if(!$model)
        {
            throw new Exception('task_like with id = ' . $taskLike->getId() . ' does not exists');
        }

        $this->assignProperties($model, $taskLike);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update task_like with id = ' . $taskLike->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * @param TaskLikeEntity $taskLike
     * @return TaskLikeEntity
     * @throws Exception
     */
    public function delete(TaskLikeEntity $taskLike)
    {
        $model = TaskLike::findOne(['id' => $taskLike->getId()]);

        if(!$model)
        {
            throw new Exception('task_like with id = ' . $taskLike->getId() . ' does not exists');
        }

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete task_like with id = ' . $taskLike->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param TaskLike $model
     * @param TaskLikeEntity $taskLike
     */
    protected function assignProperties(&$model, &$taskLike)
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
    protected function buildEntity(TaskLike $model)
    {
        return new TaskLikeEntity($model->task_id, $model->user_id, $model->liked,$model->id,
                                  $model->created_at, $model->updated_at);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param TaskLike[] $models
     * @return TaskLikeEntity[]
     */
    protected function buildEntities(array $models)
    {
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


    // #################### UNIQUE METHODS OF CLASS ######################


}