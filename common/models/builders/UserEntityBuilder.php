<?php

namespace common\models\builders;

use common\models\activerecords\Users;
use common\models\entities\UserEntity;

/**
 * Class UserEntityBuilder
 * @package common\models\builders
 */
class UserEntityBuilder
{/**
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
        $model->username = $user->getUsername();
        $model->password = $user->getPassword();
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
     * @param Users $model
     * @return UserEntity
     */
    public function buildEntity(Users $model)
    {
        $participants = null;

        if ($model->isRelationPopulated('participants')) {
            $participants = ($model->participants) ? ParticipantEntityBuilder::instance()->buildEntities($model->participants) : null;
        }

        return new UserEntity(
            $model->username, 
            $model->password,
            $model->email,
            $model->phone,
            $model->first_name, 
            $model->second_name,
            $model->last_name, 
            $model->avatar, 
            $model->auth_key,
            $model->password_reset_token, 
            $model->id, 
            $model->created_at,
            $model->updated_at, 
            $model->deleted,
            $participants
        );
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