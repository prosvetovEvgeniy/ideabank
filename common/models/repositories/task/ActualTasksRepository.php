<?php

namespace common\models\repositories\task;


use common\models\activerecords\Task;
use common\models\builders\TaskEntityBuilder;
use common\models\entities\TaskEntity;
use common\models\interfaces\IRepository;
use common\models\interfaces\IEntity;

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

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return TaskEntity[]|IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Task::find()->where(['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL])
                              ->andWhere(['deleted' => false])
                              ->andWhere('id IN (SELECT task_id FROM comment WHERE private = FALSE AND deleted = FALSE GROUP BY task_id ORDER BY COUNT(*) DESC)')
                              ->with('project')
                              ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Task::find()->where(['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL])
                                 ->andWhere(['deleted' => false])
                                 ->andWhere('id IN (SELECT task_id FROM comment WHERE private = FALSE AND deleted = FALSE GROUP BY task_id)')
                                 ->count();
    }
}