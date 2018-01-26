<?php

namespace common\models\repositories;


use common\models\activerecords\Comment;
use common\models\activerecords\Task;
use common\models\builders\TaskEntityBuilder;
use common\models\entities\TaskEntity;
use common\models\interfaces\IRepository;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

/**
 * Class ActualTasksRepository
 * @package common\models\repositories
 *
 * @property TaskEntityBuilder $builderBehavior
 */
class ActualTasksRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new TaskEntityBuilder();
    }

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
                                   ->where('t.visibility_area = ' . TaskEntity::VISIBILITY_AREA_ALL)
                                   ->groupBy('c.task_id')
                                   ->orderBy('COUNT(*) DESC')
                                   ->offset($offset)
                                   ->limit($limit)
                                   ->all();

        $tasks = Task::find()->where(['in', 'id', ArrayHelper::getColumn($comments, 'task_id')])->all();

        return $this->builderBehavior->buildEntities($tasks);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Comment::find()->addSelect('c.task_id')
                                    ->from('comment c')
                                    ->leftJoin('task t', 'c.task_id = t.id')
                                    ->where('t.visibility_area = ' . TaskEntity::VISIBILITY_AREA_ALL)
                                    ->groupBy('c.task_id')
                                    ->count();
    }
}