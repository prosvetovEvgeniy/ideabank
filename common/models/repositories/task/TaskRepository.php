<?php

namespace common\models\repositories\task;

use common\models\activerecords\Task;
use common\models\builders\TaskEntityBuilder;
use common\models\entities\ProjectEntity;
use common\models\entities\TaskEntity;
use common\models\entities\UserEntity;
use common\models\interfaces\IEntity;
use common\models\interfaces\IRepository;
use common\models\repositories\participant\ParticipantRepository;
use yii\db\Exception;
use Yii;

/**
 * Class TaskRepository
 * @package common\models\repositories
 *
 * @property TaskEntityBuilder $builderBehavior
 */
class TaskRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new TaskEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return TaskRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return TaskEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Task::findOne($condition);

        if (!$model) {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @param array $with
     * @return TaskEntity[]|IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    ) {
        $models = Task::find()->where($condition)
                              ->with($with)
                              ->offset($offset)
                              ->limit($limit)
                              ->orderBy($orderBy)
                              ->with($with)
                              ->all();

        return $this->builderBehavior->buildEntities($models);
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

        $this->builderBehavior->assignProperties($model, $task);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save task with title = ' . $task->getTitle());
        }

        return $this->builderBehavior->buildEntity($model);
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

        if (!$model) {
            throw new Exception('Task with id = ' . $task->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $task);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot update task with id = ' . $task->getId());
        }

        return $this->builderBehavior->buildEntity($model);
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

        if (!$model) {
            throw new Exception('Task with id = ' . $task->getId() . ' does not exists');
        }

        if ($model->deleted) {
            throw new Exception('Task with id = ' . $task->getId() . ' already deleted');
        }

        $model->deleted = true;

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot delete task with id = ' . $task->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Task::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountTasks(ProjectEntity $project)
    {
        $condition = $this->getConditionOnAllTasks($project);

        return $this->getTotalCountByCondition($condition);
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountCompletedTasks(ProjectEntity $project)
    {
        $condition = $this->getConditionOnCompletedTasks($project);

        return $this->getTotalCountByCondition($condition);
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountNotCompletedTasks(ProjectEntity $project)
    {
        $condition = $this->getConditionOnNotCompletedTasks($project);

        return $this->getTotalCountByCondition($condition);
    }

    /**
     * @param ProjectEntity $project
     * @return int
     */
    public function getAmountMergedTasks(ProjectEntity $project)
    {
        $condition = $this->getConditionOnMergedTasks($project);

        return $this->getTotalCountByCondition($condition);
    }

    /**
     * @param ProjectEntity $project
     * @param UserEntity $user
     * @return int
     */
    public function getAmountTasksByAuthorForProject(ProjectEntity $project, UserEntity $user)
    {
        $condition = $this->getConditionByAuthorForProject($project, $user);

        return $this->getTotalCountByCondition($condition);
    }

    /**
     * @param ProjectEntity $project
     * @return array
     */
    public function getConditionOnAllTasks(ProjectEntity $project)
    {
        return [
            'project_id' => $project->getId(),
            'deleted' => false
        ];
    }

    /**
     * @param ProjectEntity $project
     * @return array
     */
    public function getConditionOnCompletedTasks(ProjectEntity $project)
    {
        return [
            'project_id' => $project->getId(),
            'status' => TaskEntity::STATUS_COMPLETED,
            'deleted' => false
        ];
    }

    /**
     * @param ProjectEntity $project
     * @return array
     */
    public function getConditionOnNotCompletedTasks(ProjectEntity $project)
    {
        return [
            'and',
            ['project_id' => $project->getId()],
            ['deleted' => false],
            ['or', ['status' => [TaskEntity::STATUS_ON_CONSIDERATION, TaskEntity::STATUS_IN_PROGRESS]]]
        ];
    }

    /**
     * @param ProjectEntity $project
     * @return array
     */
    public function getConditionOnMergedTasks(ProjectEntity $project)
    {
        return [
            'project_id' => $project->getId(),
            'status' => TaskEntity::STATUS_MERGED,
            'deleted' => false
        ];
    }

    /**
     * @param ProjectEntity $project
     * @param UserEntity $user
     * @return array
     */
    public function getConditionByAuthorForProject(ProjectEntity $project, UserEntity $user)
    {
        return [
            'project_id' => $project->getId(),
            'author_id' => $user->getId(),
            'deleted' => false
        ];
    }

    public function getConditionOnOwnTasks()
    {
        $participants = ParticipantRepository::instance()->getParticipantsInProjects();

        $projectIds = [];

        foreach ($participants as $participant) {
            $projectIds[] = $participant->getProjectId();
        }

        return [
            'and',
            ['author_id' => Yii::$app->user->getId()],
            ['in', 'project_id', $projectIds],
            ['deleted' => false]
        ];
    }
}