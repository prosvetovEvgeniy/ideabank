<?php

namespace common\models\repositories\rbac;


use common\models\activerecords\AuthLog;
use common\models\builders\AuthLogEntityBuilder;
use common\models\interfaces\IRepository;
use common\models\entities\AuthLogEntity;
use common\models\interfaces\IEntity;
use Exception;
use Yii;

/**
 * Class AuthLogRepository
 * @package common\models\repositories\rbac
 *
 * @property AuthLogEntityBuilder $builderBehavior
 */
class AuthLogRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new AuthLogEntityBuilder();
    }

    /**
     * @return AuthLogRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @return AuthLogEntity|null
     */
    public function findOne(array $condition)
    {
        $model = AuthLog::findOne($condition);

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
     * @return AuthLogEntity[]|IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    ) {
        $models = AuthLog::find()->where($condition)
                                 ->with($with)
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($orderBy)
                                 ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) AuthLog::find()->where($condition)->count();
    }

    /**
     * @param AuthLogEntity $authLog
     * @return AuthLogEntity
     * @throws Exception
     */
    public function add(AuthLogEntity $authLog)
    {
        $model = new AuthLog();

        $this->builderBehavior->assignProperties($model, $authLog);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save auth_log with id = ' . $authLog->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param AuthLogEntity $authLog
     * @return AuthLogEntity
     * @throws Exception
     */
    public function update(AuthLogEntity $authLog)
    {
        $model = AuthLog::findOne(['id' => $authLog->getId()]);

        if (!$model) {
            throw new Exception('auth_log with id = ' . $authLog->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $authLog);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save auth_log with id = ' . $authLog->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param AuthLogEntity $authLog
     * @return AuthLogEntity
     * @throws Exception
     * @throws \Throwable
     */
    public function delete(AuthLogEntity $authLog)
    {
        $model = AuthLog::findOne(['id' => $authLog->getId()]);

        if (!$model) {
            throw new Exception('auth_log with id = ' . $authLog->getId() . ' does not exists');
        }

        if (!$model->delete()) {
            Yii::error($model->errors);
            throw new Exception('Cannot delete auth_log with id = ' . $authLog->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }
}