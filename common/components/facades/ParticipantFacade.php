<?php

namespace common\components\facades;

use common\models\entities\AuthAssignmentEntity;
use common\models\entities\ParticipantEntity;
use common\models\repositories\participant\ParticipantRepository;
use yii\db\Exception;
use Yii;

/**
 * Class ParticipantFacade
 * @package common\components\facades
 *
 * @property AuthFacade $authFacade
 */
class ParticipantFacade
{
    private $authFacade;

    public function __construct()
    {
        $this->authFacade = new AuthFacade();
    }

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
            return $this->joinExistParticipant($participantExist);
        }

        $participant = ParticipantRepository::instance()->add($participant);

        $this->authFacade->addAuth(
            $participant->getId(),
            $participant,
            AuthAssignmentEntity::ROLE_ON_CONSIDERATION
        );

        return $participant;
    }

    /**
     * Добаляет пользователя если он когда-то
     * находился в проекте, но потом покинул его
     *
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function joinExistParticipant(ParticipantEntity $participant)
    {
        $this->authFacade->updateAuth(
            $participant->getId(),
            $participant,
            AuthAssignmentEntity::ROLE_ON_CONSIDERATION
        );

        $participant->setApproved(false);
        $participant->setApprovedAt();
        $participant->setDeleted(false);
        $participant->setDeletedAt();

        /**
         * если пользователь был забанен,
         * а потом удалился из проекта, то
         * добавить его на рассмотрение, после этого
         * добавить в проект, после этого забанить
         */
        if ($participant->getBlocked()) {
            return $this->blockParticipant(
                $this->addParticipant(
                    ParticipantRepository::instance()->update($participant)
                )
            );
        }

        return ParticipantRepository::instance()->update($participant);
    }

    /**
     * @param ParticipantEntity $participant
     * @return ParticipantEntity
     * @throws Exception
     */
    public function addParticipant(ParticipantEntity $participant)
    {
        $this->authFacade->updateAuth(
            Yii::$app->user->getParticipantId($participant->getProjectId()),
            $participant,
            AuthAssignmentEntity::ROLE_USER
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
    public function blockParticipant(ParticipantEntity $participant)
    {
        $this->authFacade->updateAuth(
            Yii::$app->user->getParticipantId($participant->getProjectId()),
            $participant,
            AuthAssignmentEntity::ROLE_BLOCKED
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
        $this->authFacade->updateAuth(
            Yii::$app->user->getParticipantId($participant->getProjectId()),
            $participant,
            $participant->getPreviousAuthLog()->getRoleName()
        );

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
        $this->authFacade->updateAuth(
            Yii::$app->user->getParticipantId($participant->getProjectId()),
            $participant,
            AuthAssignmentEntity::ROLE_DELETED
        );

        $participant->setApproved(false);
        $participant->setApprovedAt();

        return ParticipantRepository::instance()->delete($participant);
    }
}