<?php

namespace common\components\facades;

use common\models\entities\AuthAssignmentEntity;
use common\models\entities\AuthLogEntity;
use common\models\entities\ParticipantEntity;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\rbac\AuthAssignmentRepository;
use common\models\repositories\rbac\AuthLogRepository;
use yii\db\Exception;
use Yii;

/**
 * Class ParticipantFacade
 * @package common\components\facades
 */
class ParticipantFacade
{
    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function joinParticipant(ParticipantEntity $participant)
    {
        $participantExist = ParticipantRepository::instance()->findOne([
            'user_id'    => $participant->getUserId(),
            'project_id' => $participant->getProjectId(),
        ]);

        //если пользователь уже был присоединен к проекту, но покинул его
        if ($participantExist) {

            $authAssignment = $participantExist->getAuthAssignment();
            $authAssignment->setItemName(AuthAssignmentEntity::ROLE_ON_CONSIDERATION);

            AuthAssignmentRepository::instance()->update($authAssignment);

            AuthLogRepository::instance()->add(
                new AuthLogEntity(
                    $participantExist->getId(),
                    $participantExist->getId(),
                    AuthAssignmentEntity::ROLE_ON_CONSIDERATION
                )
            );

            $participantExist->setDeleted(false);
            $participantExist->setDeletedAt();

            return ParticipantRepository::instance()->update($participantExist);
        }

        $participant = ParticipantRepository::instance()->add($participant);

        AuthAssignmentRepository::instance()->add(
            new AuthAssignmentEntity(
                AuthAssignmentEntity::ROLE_ON_CONSIDERATION,
                $participant->getId()
            )
        );

        AuthLogRepository::instance()->add(
            new AuthLogEntity(
                $participant->getId(),
                $participant->getId(),
                AuthAssignmentEntity::ROLE_ON_CONSIDERATION
            )
        );

        return $participant;
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function addParticipant(ParticipantEntity $participant)
    {
        $auth = $participant->getAuthAssignment();

        $auth->setItemName(AuthAssignmentEntity::ROLE_USER);

        AuthAssignmentRepository::instance()->update($auth);

        AuthLogRepository::instance()->add(
            new AuthLogEntity(
                Yii::$app->user->getId(),
                $participant->getId(),
                AuthAssignmentEntity::ROLE_USER
            )
        );

        $participant->setApproved(true);
        $participant->setApprovedAt(time());

        return ParticipantRepository::instance()->update($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     * @throws \Throwable
     */
    public function cancelParticipant(ParticipantEntity $participant)
    {
        $authAssignment = $participant->getAuthAssignment();
        $authAssignment->setItemName(AuthAssignmentEntity::ROLE_DELETED);

        AuthAssignmentRepository::instance()->update($authAssignment);

        AuthLogRepository::instance()->add(
            new AuthLogEntity(
                Yii::$app->user->getId(),
                $participant->getId(),
                AuthAssignmentEntity::ROLE_DELETED
            )
        );

        return ParticipantRepository::instance()->delete($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function blockParticipant(ParticipantEntity $participant)
    {
        $authAssignment = $participant->getAuthAssignment();
        $authAssignment->setItemName(AuthAssignmentEntity::ROLE_BLOCKED);

        AuthAssignmentRepository::instance()->update($authAssignment);

        AuthLogRepository::instance()->add(
            new AuthLogEntity(
                Yii::$app->user->getId(),
                $participant->getId(),
                AuthAssignmentEntity::ROLE_BLOCKED
            )
        );

        $participant->setBlocked(true);
        $participant->setBlockedAt(time());
        $participant->setApproved(false);

        return ParticipantRepository::instance()->update($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function unBlockParticipant(ParticipantEntity $participant)
    {
        $participant->setBlocked(false);
        $participant->setBlockedAt();
        $participant->setApproved(true);
        $participant->setApprovedAt(time());

        return ParticipantRepository::instance()->update($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     * @throws \Throwable
     */
    public function deleteParticipant(ParticipantEntity $participant)
    {
        $authAssignment = AuthAssignmentRepository::instance()->findOne([
            'user_id' => $participant->getId()
        ]);

        if ($authAssignment) {
            AuthAssignmentRepository::instance()->delete($authAssignment);
        }

        $participant->setApproved(false);
        $participant->setApprovedAt();

        return ParticipantRepository::instance()->delete($participant);
    }
}