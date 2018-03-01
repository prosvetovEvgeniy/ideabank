<?php

namespace common\components\facades;

use common\models\entities\AuthAssignmentEntity;
use common\models\entities\AuthLogEntity;
use common\models\entities\ParticipantEntity;
use common\models\repositories\rbac\AuthAssignmentRepository;
use common\models\repositories\rbac\AuthLogRepository;

/**
 * Class AuthFacade
 * @package common\components\facades
 */
class AuthFacade
{
    /**
     * @param int $changerId
     * @param ParticipantEntity $participant
     * @param string $roleName
     * @return AuthAssignmentEntity
     * @throws \yii\db\Exception
     */
    public function addAuth(int $changerId, ParticipantEntity $participant, string $roleName)
    {
        AuthLogRepository::instance()->add(
            new AuthLogEntity(
                $changerId,
                $participant->getId(),
                $roleName
            )
        );

        return AuthAssignmentRepository::instance()->add(
            new AuthAssignmentEntity(
                $roleName,
                $participant->getId()
            )
        );
    }

    /**
     * @param ParticipantEntity $participant
     * @param int $changerId
     * @param string $roleName
     * @return AuthAssignmentEntity
     * @throws \yii\db\Exception
     */
    public function updateAuth(int $changerId, ParticipantEntity $participant, string $roleName)
    {
        AuthLogRepository::instance()->add(
            new AuthLogEntity(
                $changerId,
                $participant->getId(),
                $roleName
            )
        );

        $authAssignment = $participant->getAuthAssignment();
        $authAssignment->setItemName($roleName);

        return AuthAssignmentRepository::instance()->update($authAssignment);
    }
}