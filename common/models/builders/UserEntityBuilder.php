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
     * @param Users $model
     * @param UserEntity $user
     */
    public function assignProperties(Users &$model, UserEntity &$user)
    {
        $model->username = Html::encode($user->getUsername());
        $model->password = $user->getPassword();
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
     * @param array $models
     * @return UserEntity[]
     */
    public function buildEntities(array $models)
    {
        if (!$models) {
            return [];
        }

        $entities = [];

        foreach ($models as $model) {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }
}