<?php

namespace common\models\repositories;


use common\models\activerecords\Task;
use common\models\entities\ProjectEntity;
use common\models\entities\TaskEntity;
use yii\db\Exception;
use Yii;

class TaskRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return TaskRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return TaskEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = Task::findOne($condition);

        if(!$model)
        {
            throw new Exception('Task with ' . json_encode($condition) . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Task with ' . json_encode($condition) . ' already deleted');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return TaskEntity[]
     */
    public function findAll(array $condition)
    {
        $models = Task::findAll($condition);

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param TaskEntity $task
     * @return TaskEntity
     * @throws Exception
     */
    public function add(TaskEntity $task)
    {
        $model = new Task();

        $this->assignProperties($model, $task);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save task with title = ' . $task->getTitle());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param TaskEntity $task
     * @return TaskEntity
     * @throws Exception
     */
    public function update(TaskEntity $task)
    {
        $model = Task::findOne(['id' => $task->getId()]);

        if(!$model)
        {
            throw new Exception('Task with id = ' . $task->getId() . ' does not exists');
        }

        $this->assignProperties($model, $task);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update task with id = ' . $task->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param TaskEntity $task
     * @return TaskEntity
     * @throws Exception
     */
    public function delete(TaskEntity $task)
    {
        $model = Task::findOne(['id' => $task->getId()]);

        if(!$model)
        {
            throw new Exception('Task with id = ' . $task->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Task with id = ' . $task->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete task with id = ' . $task->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Task $model
     * @param TaskEntity $task
     */
    protected function assignProperties(&$model, &$task)
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
    protected function buildEntity(Task $model)
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


    /**
     * Возвращает массив сущностей завершенных задач
     *
     * @param ProjectEntity $project
     * @return TaskEntity[]
     */
    public function findCompletedTasks(ProjectEntity $project)
    {
        return $this->findAll(['project_id' => $project->getId(), 'status' => TaskEntity::STATUS_COMPLETED,
                               'deleted' => false]);
    }

    /**
     * Возвращает массив сущностей не завершенных задач
     *
     * @param ProjectEntity $project
     * @return TaskEntity[]
     */
    public function findNotCompletedTasks(ProjectEntity $project)
    {
        /** @var Task[] $models */
        $models = Task::find()->where(['project_id' => $project->getId()])
                              ->andWhere(['deleted' => false])
                              ->andWhere(['status' => TaskEntity::STATUS_ON_CONSIDERATION])
                              ->orWhere(['status' => TaskEntity::STATUS_IN_PROGRESS])
                              ->all();

        return $this->buildEntities($models);
    }

    /**
     * Возвращает массив объединенных сущностей
     *
     * @param ProjectEntity $project
     * @return TaskEntity[]
     */
    public function findMergedTasks(ProjectEntity $project)
    {
        return $this->findAll(['project_id' => $project->getId(), 'status' => TaskEntity::STATUS_MERGED,
                               'deleted' => false]);
    }

}