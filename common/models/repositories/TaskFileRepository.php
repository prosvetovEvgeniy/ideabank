<?php

namespace common\models\repositories;


use common\models\activerecords\TaskFile;
use common\models\entities\TaskFileEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;


class TaskFileRepository implements IRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return TaskFileRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return TaskFileEntity|null
     */
    public function findOne(array $condition)
    {
        $model = TaskFile::findOne($condition);

        if(!$model || $model->deleted)
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
     * @return TaskFileEntity[]
     */
    public function findAll(array $condition, int $limit = -1, int $offset = null, string $orderBy = null)
    {
        $models = TaskFile::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param TaskFileEntity $taskFile
     * @return TaskFileEntity
     * @throws Exception
     */
    public function add(TaskFileEntity $taskFile)
    {
        $model = new TaskFile();

        $this->assignProperties($model, $taskFile);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save task_file with hash_name = ' . $taskFile->getHashName());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param TaskFileEntity $taskFile
     * @return TaskFileEntity
     * @throws Exception
     */
    public function update(TaskFileEntity $taskFile)
    {
        $model = TaskFile::findOne(['id' => $taskFile->getId()]);

        if(!$model)
        {
            throw new Exception('Task_file with id = ' . $taskFile->getId() . ' does not exists');
        }

        $this->assignProperties($model, $taskFile);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update task_file with id = ' . $taskFile->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param TaskFileEntity $taskFile
     * @return TaskFileEntity
     * @throws Exception
     */
    public function delete(TaskFileEntity $taskFile)
    {
        $model = TaskFile::findOne(['id' => $taskFile->getId()]);

        if(!$model)
        {
            throw new Exception('Task_file with id = ' . $taskFile->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Task_file with id = ' . $taskFile->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete task_file with id = ' . $taskFile->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param TaskFile $model
     * @param TaskFileEntity $taskFile
     */
    protected function assignProperties(&$model, &$taskFile)
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
        return new TaskFileEntity($model->task_id, $model->hash_name, $model->original_name, $model->id,
                                  $model->created_at, $model->deleted);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param TaskFile[] $models
     * @return TaskFileEntity[]
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
        return TaskFile::find()->where($condition)->count();
    }
}