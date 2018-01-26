<?php

namespace common\models\builders;


use common\models\activerecords\Users;
use common\models\entities\UserEntity;
use yii\helpers\Html;

class UserEntityBuilder
{
    /**
     * @return UserEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Users $model
     * @param UserEntity $user
     * @throws \yii\base\Exception
     */
    public function assignProperties(Users &$model, UserEntity &$user)
    {
        $model->username = Html::encode($user->getUsername());
        $model->password = $user->getPasswordHash();
        $model->email = Html::encode($user->getEmail());
        $model->phone = Html::encode($user->getPhone());
        $model->first_name = Html::encode($user->getFirstName());
        $model->second_name = Html::encode($user->getSecondName());
        $model->last_name = Html::encode($user->getLastName());
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
    public function buildEntities(array $models)
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
}