<?php

namespace common\components\facades;

use common\models\entities\AuthAssignmentEntity;
use common\models\entities\ParticipantEntity;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\rbac\AuthAssignmentRepository;
use yii\db\Exception;

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
            if($participantExist->getDeleted()) {

                $participantExist->setApproved(false);
                $participantExist->setApprovedAt();
                $participantExist->setDeleted(false);
                $participantExist->setDeletedAt();

                return ParticipantRepository::instance()->update($participantExist);
            }
        }

        return ParticipantRepository::instance()->add($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function addParticipant(ParticipantEntity $participant)
    {
        AuthAssignmentRepository::instance()->add(
            new AuthAssignmentEntity(
                AuthAssignmentEntity::ROLE_USER,
                $participant->getId()
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
     */
    public function cancelParticipant(ParticipantEntity $participant)
    {
        return ParticipantRepository::instance()->delete($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function blockParticipant(ParticipantEntity $participant)
    {
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