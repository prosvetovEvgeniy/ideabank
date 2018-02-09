<?php

namespace common\models\repositories;


use common\models\activerecords\TaskNotice;
use common\models\builders\TaskNoticeBuilder;
use common\models\interfaces\INotice;
use common\models\interfaces\IRepository;
use common\models\entities\TaskNoticeEntity;
use common\models\interfaces\IEntity;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class TaskNoticeRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new TaskNoticeBuilder();
    }

    /**
     * @return TaskNoticeRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @return TaskNoticeEntity|IEntity|INotice|null
     */
    public function findOne(array $condition)
    {
        $model = TaskNotice::findOne($condition);

        if(!$model)
        {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return TaskNoticeEntity[]|IEntity[]|INotice[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = TaskNotice::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) TaskNotice::find()->where($condition)->count();
    }

    /**
     * @param TaskNoticeEntity $taskNotice
     * @return TaskNoticeEntity
     * @throws Exception
     */
    public function add(TaskNoticeEntity $taskNotice)
    {
        $model = new TaskNotice();

        $this->builderBehavior->assignProperties($model, $taskNotice);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save task_notice with id = ' . $taskNotice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param TaskNoticeEntity $taskNotice
     * @return TaskNoticeEntity
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(TaskNoticeEntity $taskNotice)
    {
        $model = TaskNotice::findOne(['id' => $taskNotice->getId()]);

        if(!$model)
        {
            throw new Exception('TaskNotice with id = ' . $taskNotice->getId() . ' does not exists');
        }

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete task_notice with id = ' . $taskNotice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return TaskNoticeEntity[]|IEntity[]|INotice[]
     */
    public function deleteAll(array $condition)
    {
        $taskNotices = $this->findAll($condition);

        $ids = ArrayHelper::getColumn($taskNotices, function($taskNotice) {
           /**
            * @var TaskNoticeEntity $taskNotice
            */
           return $taskNotice->getId();
        });

        TaskNotice::deleteAll(['in', 'id', $ids]);

        return $taskNotices;
    }
}