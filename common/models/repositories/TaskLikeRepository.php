<?php

namespace common\models\repositories;


use common\models\activerecords\TaskLike;
use common\models\entities\TaskEntity;
use common\models\entities\TaskLikeEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

class TaskLikeRepository implements IRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return TaskLikeRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return TaskLikeEntity|null
     */
    public function findOne(array $condition)
    {
        $model = TaskLike::findOne($condition);

        if(!$model)
        {
            return null;
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return TaskLikeEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = TaskLike::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

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
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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

    /**
     * @param array $condition
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition)
    {
        return TaskLike::find()->where($condition)->count();
    }

    // #################### UNIQUE METHODS OF CLASS ######################

    /**
     * @param TaskEntity $task
     * @return int
     */
    public function getAmountLikes(TaskEntity $task)
    {
        return TaskLike::find()->where(['task_id' => $task->getId(), 'liked' => true])->count();
    }

    /**
     * @param TaskEntity $task
     * @return int|string
     */
    public function getAmountDislikes(TaskEntity $task)
    {
        return TaskLike::find()->where(['task_id' => $task->getId(), 'liked' => false])->count();
    }
}