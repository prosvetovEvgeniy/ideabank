<?php

namespace common\models\repositories\rbac;

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
     * @return AuthAssignmentEntity[]|\common\models\interfaces\IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    ) {
        $models = AuthAssignment::find()->where($condition)
                                        ->with($with)
                                        ->offset($offset)
                                        ->limit($limit)
                                        ->orderBy($orderBy)
                                        ->all();

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

        if (!$model->save()) {
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

        if (!$model) {
            throw new Exception('auth_assignment with user_id = ' . $authAssignment->getUserId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $authAssignment);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot update auth_assignment with user_id = ' . $authAssignment->getUserId());
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