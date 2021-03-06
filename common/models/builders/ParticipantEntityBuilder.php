<?php

namespace common\models\builders;

use common\models\activerecords\Participant;
use common\models\entities\ParticipantEntity;

/**
 * Class ParticipantEntityBuilder
 * @package common\models\builders
 */
class ParticipantEntityBuilder
{
    /**
     * @return ParticipantEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Participant $model
     * @param ParticipantEntity $participant
     */
    public function assignProperties(Participant &$model, ParticipantEntity &$participant)
    {
        $model->user_id = $participant->getUserId();
        $model->company_id = $participant->getCompanyId();
        $model->project_id = $participant->getProjectId();
        $model->approved = $participant->getApproved();
        $model->approved_at = $participant->getApprovedAt();
        $model->blocked = $participant->getBlocked();
        $model->blocked_at = $participant->getBlockedAt();
        $model->deleted_at = $participant->getDeletedAt();
        $model->deleted = $participant->getDeleted();
    }

    /**
     * @param Participant $model
     * @return ParticipantEntity
     */
    public function buildEntity(Participant $model)
    {
        $project = null;
        $user = null ;
        $company = null;
        $authAssignment = null;

        if ($model->isRelationPopulated('project')) {
            $project = ($model->project) ? ProjectEntityBuilder::instance()->buildEntity($model->project) : null;
        }

        if ($model->isRelationPopulated('user')) {
            $user = ($model->user) ? UserEntityBuilder::instance()->buildEntity($model->user) : null;
        }

        if ($model->isRelationPopulated('company')) {
            $company = ($model->company) ? CompanyEntityBuilder::instance()->buildEntity($model->company) : null;
        }

        if ($model->isRelationPopulated('authAssignment')) {
            $authAssignment = ($model->authAssignment) ? AuthAssignmentEntityBuilder::instance()->buildEntity($model->authAssignment) : null;
        }

        return new ParticipantEntity(
            $model->user_id, 
            $model->company_id, 
            $model->project_id,
            $model->approved, 
            $model->approved_at,
            $model->blocked, 
            $model->blocked_at,
            $model->id, 
            $model->created_at, 
            $model->updated_at, 
            $model->deleted_at,
            $model->deleted, 
            $project,
            $user, 
            $company, 
            $authAssignment
        );
    }

    /**
     * @param array $models
     * @return ParticipantEntity[]
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