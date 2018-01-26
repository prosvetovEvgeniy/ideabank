<?php

namespace common\models\repositories;


use common\models\activerecords\AuthAssignment;
use common\models\entities\AuthAssignmentEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

class AuthAssignmentRepository implements IRepository
{
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

        return $this->buildEntity($model);
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

        return $this->buildEntities($models);
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

        $this->assignProperties($model, $authAssignment);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save auth_assignment with user_id = ' . $authAssignment->getUserId());
        }

        return $this->buildEntity($model);
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

        $this->assignProperties($model, $commentLike);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update auth_assignment with user_id = ' . $authAssignment->getUserId());
        }

        return $this->buildEntity($model);
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

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param AuthAssignment $model
     * @param AuthAssignmentEntity $authAssignment
     */
    protected function assignProperties(&$model, &$authAssignment)
    {
        $model->itemName = $authAssignment->getItemName();
        $model->user_id = $authAssignment->getUserId();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param AuthAssignment $model
     * @return AuthAssignmentEntity
     */
    protected function buildEntity(AuthAssignment $model)
    {
        return new AuthAssignmentEntity($model->item_name, $model->user_id, $model->created_at);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param AuthAssignment[] $models
     * @return AuthAssignmentEntity[]
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