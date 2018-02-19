<?php

namespace common\models\repositories\user;

use common\models\activerecords\Users;
use common\models\builders\UserEntityBuilder;
use common\models\entities\UserEntity;
use common\models\interfaces\IEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class UserRepository
 * @package common\models\repositories
 *
 * @property UserEntityBuilder $builderBehavior
 */
class UserRepository implements IRepository
{
    public $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new UserEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return UserRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return UserEntity|null
     */
    public function findOne(array $condition)
    {
        /**
         * @var Users $model
         */
        $model = Users::find()->where($condition)->one();

        if (!$model || $model->deleted) {
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
     * @param string|null $orderBy
     * @return UserEntity[]|IEntity
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Users::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param UserEntity $user
     * @return UserEntity
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function add(UserEntity $user)
    {
        $model = new Users();

        $this->builderBehavior->assignProperties($model, $user);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save user with username = ' . $user->getUsername());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param UserEntity $user
     * @return UserEntity
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function update(UserEntity $user)
    {
        $model = Users::findOne(['id' => $user->getId()]);

        if (!$model) {
            throw new Exception('User with id = ' . $user->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $user);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot update user with id = ' . $user->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param UserEntity $user
     * @return UserEntity
     * @throws Exception
     */
    public function delete(UserEntity $user)
    {
        $model = Users::findOne(['id' => $user->getId()]);

        if (!$model) {
            throw new Exception('User with id = ' . $user->getId() . ' does not exists');
        }

        if ($model->deleted) {
            throw new Exception('User with id = ' . $user->getId() . ' already deleted');
        }

        $model->deleted = true;

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot delete user with id = ' . $user->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Users::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################
}