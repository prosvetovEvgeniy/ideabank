<?php

namespace common\components\facades;

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
    public function addParticipant(ParticipantEntity $participant)
    {

    }

    public function cancelParticipant(ParticipantEntity $participant)
    {

    }

    public function blockParticipant(ParticipantEntity $participant)
    {

    }

    public function unBlockParticipant(ParticipantEntity $participant)
    {

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
        $participant->setApprovedAt(null);

        return ParticipantRepository::instance()->delete($participant);
    }
}