<?php

namespace common\models\repositories;


use common\models\activerecords\AuthAssignment;
use common\models\builders\AuthAssignmentEntityBuilder;
use common\models\entities\AuthAssignmentEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class AuthAssignmentRepository
 * @package common\models\repositories
 *
 * @property AuthAssignmentEntityBuilder $builderBehavior
 */
class AuthAssignmentRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new AuthAssignmentEntityBuilder();
    }
    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return AuthAssignmentRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return AuthAssignmentEntity|null
     */
    public function findOne(array $condition)
    {
        $model = AuthAssignment::findOne($condition);

        if(!$model)
        {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string $orderBy
     * @return AuthAssignmentEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = AuthAssignment::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param AuthAssignmentEntity $authAssignment
     * @return AuthAssignmentEntity
     * @throws Exception
     */
    public function add(AuthAssignmentEntity $authAssignment)
    {
        $model = new AuthAssignment();

        $this->builderBehavior->assignProperties($model, $authAssignment);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save auth_assignment with user_id = ' . $authAssignment->getUserId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param AuthAssignmentEntity $authAssignment
     * @return AuthAssignmentEntity
     * @throws Exception
     */
    public function update(AuthAssignmentEntity $authAssignment)
    {
        $model = AuthAssignment::findOne(['user_id' => $authAssignment->getUserId()]);

        if(!$model)
        {
            throw new Exception('auth_assignment with user_id = ' . $authAssignment->getUserId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $commentLike);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update auth_assignment with user_id = ' . $authAssignment->getUserId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param AuthAssignmentEntity $authAssignment
     * @return AuthAssignmentEntity
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(AuthAssignmentEntity $authAssignment)
    {
        $model = AuthAssignment::findOne(['user_id' => $authAssignment->getUserId()]);

        if(!$model)
        {
            throw new Exception('auth_assignment with user_id = ' . $authAssignment->getUserId() . ' does not exists');
        }

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete auth_assignment with user_id = ' . $authAssignment->getUserId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) AuthAssignment::find()->where($condition)->count();
    }

    // #################### UNIQUE METHODS OF CLASS ######################
}