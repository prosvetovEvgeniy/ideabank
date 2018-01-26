<?php

namespace common\models\builders;


use common\models\activerecords\AuthAssignment;
use common\models\entities\AuthAssignmentEntity;
use yii\helpers\Html;

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
        $model->itemName = Html::encode($authAssignment->getItemName());
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
        return new AuthAssignmentEntity($model->item_name, $model->user_id, $model->created_at);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param AuthAssignment[] $models
     * @return AuthAssignmentEntity[]
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