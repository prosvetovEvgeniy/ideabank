<?php

namespace common\models\builders;

use common\models\activerecords\AuthLog;
use common\models\entities\AuthLogEntity;

class AuthLogEntityBuilder
{
    /**
     * @return AuthLogEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param AuthLog $model
     * @param AuthLogEntity $authLog
     */
    public function assignProperties(AuthLog &$model, AuthLogEntity &$authLog)
    {
        $model->changer_id = $authLog->getChangerId();
        $model->changeable_id = $authLog->getChangeableId();
        $model->new_role_name = $authLog->getNewRoleName();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param AuthLog $model
     * @return AuthLogEntity
     */
    public function buildEntity(AuthLog $model)
    {
        $changer = null;
        $changeable = null;

        if ($model->isRelationPopulated('changer')) {
            $changer = ($model->changer) ? ParticipantEntityBuilder::instance()->buildEntity($model->changer) : null;
        }

        if ($model->isRelationPopulated('changeable')) {
            $changeable = ($model->changeable) ? ParticipantEntityBuilder::instance()->buildEntity($model->changeable) : null;
        }

        return new AuthLogEntity(
            $model->changeable_id,
            $model->new_role_name,
            $model->changer_id,
            $model->id,
            $model->created_at,
            $changer,
            $changeable
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param AuthLog[] $models
     * @return AuthLogEntity[]
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