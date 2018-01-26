<?php

namespace common\models\repositories;


use common\models\activerecords\Comment;
use common\models\activerecords\Task;
use common\models\entities\TaskEntity;
use common\models\interfaces\IRepository;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

class ActualTasksRepository implements IRepository
{
    public static function instance(): IRepository
    {
        return new self();
    }

    public function findOne(array $condition)
    {
        throw new NotSupportedException();
    }

    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $comments = Comment::find()->addSelect('c.task_id')
                                  ->from('comment c')
                                  ->leftJoin('task t', 'c.task_id = t.id')
                                  ->where('t.visibility_area = 0')
                                  ->groupBy('c.task_id')
                                  ->orderBy('COUNT(*) DESC')
                                  ->all();

        $taskIds = [];

        foreach ($comments as $comment)
        {
            $taskIds[] = $comment->task_id;
        }

        $tasks = Task::find()->where(['in', 'id', $taskIds])->all();

        return $this->buildEntities($tasks);
    }

    public function assignProperties(Task $model, TaskEntity &$task)
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

    public function getTotalCountByCondition(array $condition): int
    {
        throw new NotSupportedException();
    }
}