<?php

namespace common\models\repositories;


use common\models\activerecords\Message;
use common\models\activerecords\Users;
use common\models\entities\UserEntity;
use common\models\interfaces\IRepository;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

class CompanionRepository implements IRepository
{
    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return CompanionRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    public function findOne(array $condition)
    {
        throw new NotSupportedException();
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string $orderBy
     * @return UserEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $companionIds = Message::find()->select('companion_id')
                                       ->where($condition )
                                       ->distinct('companion_id')
                                       ->offset($offset)
                                       ->limit($limit)
                                       ->orderBy($orderBy)
                                       ->all();

        $models = Users::find()->where(['in', 'id', ArrayHelper::getColumn($companionIds,'companion_id')])->all();

        return $this->buildEntities($models);
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
     * @param array $condition
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition)
    {
        return Message::find()->select('companion_id')
                              ->where($condition )
                              ->distinct('companion_id')
                              ->count();
    }
}