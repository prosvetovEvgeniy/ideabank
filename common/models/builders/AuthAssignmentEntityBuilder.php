<?php

namespace common\models\builders;

use common\models\activerecords\AuthAssignment;
use common\models\entities\AuthAssignmentEntity;

/**
 * Class AuthAssignmentEntityBuilder
 * @package common\models\builders
 *
 */
class AuthAssignmentEntityBuilder
{
    /**
     * @return AuthAssignmentEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param AuthAssignment $model
     * @param AuthAssignmentEntity $authAssignment
     */
    public function assignProperties(&$model, &$authAssignment)
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
    public function buildEntity(AuthAssignment $model)
    {
        $user = null;

        if($model->isRelationPopulated('user'))
        {
            $user = ($model->user) ? ParticipantEntityBuilder::instance()->buildEntity($user) : null;
        }

        return new AuthAssignmentEntity(
            $model->item_name, 
            $model->user_id, 
            $model->created_at,
            $user
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param AuthAssignment[] $models
     * @return AuthAssignmentEntity[]
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