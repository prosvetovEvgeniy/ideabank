<?php

namespace common\models\repositories;


use common\models\activerecords\Users;
use common\models\entities\UserEntity;
use yii\db\Exception;
use Yii;

class UserRepository
{

    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return UserRepository
     */
    public static function instance()
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
        $model = Users::findOne($condition);

        if(!$model || $model->deleted)
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
     * @param string|null $orderBy
     * @return UserEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Users::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param UserEntity $user
     * @return UserEntity
     * @throws Exception
     */
    public function add(UserEntity $user)
    {
        $model = new Users();

        $this->assignProperties($model, $user);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save user with username = ' . $user->getUsername());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param UserEntity $user
     * @return UserEntity
     * @throws Exception
     */
    public function update(UserEntity $user)
    {
        $model = Users::findOne(['id' => $user->getId()]);

        if(!$model)
        {
            throw new Exception('User with id = ' . $user->getId() . ' does not exists');
        }

        $this->assignProperties($model, $user);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update user with id = ' . $user->getId());
        }

        return $this->buildEntity($model);
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

        if(!$model)
        {
            throw new Exception('User with id = ' . $user->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('User with id = ' . $user->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete user with id = ' . $user->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Users $model
     * @param UserEntity $user
     */
    protected function assignProperties(&$model, &$user)
    {
        $model->username = $user->getUsername();
        $model->password = $user->getPasswordHash();
        $model->email = $user->getEmail();
        $model->phone = $user->getPhone();
        $model->first_name = $user->getFirstName();
        $model->second_name = $user->getSecondName();
        $model->last_name = $user->getLastName();
        $model->avatar = $user->getAvatar();
        $model->auth_key = $user->getAuthKey();
        $model->password_reset_token = $user->getPasswordResetToken();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param Users $model
     * @return UserEntity
     */
    public function buildEntity(Users $model)
    {
        return new UserEntity($model->username, $model->password, $model->email,
                              $model->phone, $model->first_name, $model->second_name,
                              $model->last_name, $model->avatar, $model->auth_key,
                              $model->password_reset_token, $model->id, $model->created_at,
                              $model->updated_at, $model->deleted);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Users[] $models
     * @return UserEntity[]
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


    // #################### UNIQUE METHODS OF CLASS ######################


}