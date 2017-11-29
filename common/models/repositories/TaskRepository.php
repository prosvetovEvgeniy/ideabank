<?php

namespace common\models\repositories;


use common\models\activerecords\Task;
use common\models\entities\ProjectEntity;
use common\models\entities\TaskEntity;
use common\models\entities\UserEntity;
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
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null)
    {
        $models = Task::find()->where($condition)->offset($offset)->limit($limit)->all();

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
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function findCompletedTasks(ProjectEntity $project, int $limit = null, int $offset = null)
    {
        return $this->findAll(['project_id' => $project->getId(), 'status' => TaskEntity::STATUS_COMPLETED,
                               'deleted' => false], $offset, $limit);
    }

    /**
     * Возвращает массив сущностей не завершенных задач
     *
     * @param ProjectEntity $project
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function findNotCompletedTasks(ProjectEntity $project, int $limit = null, int $offset = null)
    {
        return $this->findAll([
            'and',
            ['project_id' => $project->getId()],
            ['deleted' => false],
            ['or', ['status' => [TaskEntity::STATUS_ON_CONSIDERATION, TaskEntity::STATUS_IN_PROGRESS]]]
        ], $offset, $limit);
    }

    /**
     * Возвращает массив сущностей объединенных задач
     *
     * @param ProjectEntity $project
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function findMergedTasks(ProjectEntity $project, int $limit = null, int $offset = null)
    {
        return $this->findAll(['project_id' => $project->getId(), 'status' => TaskEntity::STATUS_MERGED,
                               'deleted' => false], $offset, $limit);
    }

    /**
     * Возвращает задачи созданные определенным пользователем
     *
     * @param ProjectEntity $project
     * @param UserEntity $user
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function findTasksByAuthor(ProjectEntity $project, UserEntity $user, int $limit = null, int $offset = null)
    {
        return $this->findAll(['project_id' => $project->getId(), 'author_id' => $user->getId(),
                               'deleted' => false], $offset, $limit);
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountTasks(ProjectEntity $project)
    {
        return Task::find()->where(['project_id' => $project->getId()])
                           ->andWhere(['deleted' => false])
                           ->count();
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountCompletedTasks(ProjectEntity $project)
    {
        return Task::find()->where(['project_id' => $project->getId()])
                           ->andWhere(['status' => TaskEntity::STATUS_COMPLETED])
                           ->andWhere(['deleted' => false])
                           ->count();
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountNotCompletedTasks(ProjectEntity $project)
    {
        return Task::find()->where(['project_id' => $project->getId()])
                           ->andWhere(['deleted' => false])
                           ->andWhere(['status' => TaskEntity::STATUS_ON_CONSIDERATION])
                           ->orWhere(['status' => TaskEntity::STATUS_IN_PROGRESS])
                           ->count();
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountMergedTasks(ProjectEntity $project)
    {
        return Task::find()->where(['project_id' => $project->getId()])
                           ->andWhere(['status' => TaskEntity::STATUS_MERGED])
                           ->andWhere(['deleted' => false])
                           ->count();
    }

    /**
     * @param ProjectEntity $project
     * @param UserEntity $user
     * @return int
     */
    public function getAmountTasksByAuthor(ProjectEntity $project, UserEntity $user)
    {
        return Task::find()->where(['project_id' => $project->getId()])
                           ->andWhere(['author_id' => $user->getId()])
                           ->andWhere(['deleted' => false])
                           ->count();
    }
}