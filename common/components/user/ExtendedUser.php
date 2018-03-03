<?php

namespace common\components\user;

use common\models\entities\AuthAssignmentEntity;
use common\models\repositories\participant\ParticipantRepository;
use yii\web\User;
use Yii;

/**
 * Class ExtendedUser
 * @package common\components\user
 */
class ExtendedUser extends User
{
    /**
     * @param string $permissionName
     * @param int $projectId
     * @param int|null $userId
     * @return bool
     */
    public function is(string $permissionName, int $projectId, int $userId = null)
    {
        if (parent::getIsGuest()) {
            return false;
        }

        $userId = $userId ?? parent::getId();

        $cache = Yii::$app->cache;
        $authManager = Yii::$app->authManager;

        $cacheValue = $cache->get([$userId, $projectId]);

        if ($cacheValue !== false) {
            return in_array($permissionName, $cacheValue);
        }

        $participant = ParticipantRepository::instance()->findOne([
            'user_id' => $userId,
            'project_id' => $projectId
        ], ['authAssignment']);

        /**
         * если пользователь не участник проекта,
         * то положить в кеш пустой массив
         * (всегда будет давать false на in_array())
         */
        if (!$participant) {
            $cache->add([$userId, $projectId], []);
            return false;
        }

        /**
         * получаем список дочерних ролей,
         * вместе с текущей ролью в виде массива
         */
        $childRoles = array_keys(
            $authManager->getChildRoles(
                $participant->getAuthAssignment()->getRoleName()
            )
        );

        $cache->add([$userId, $projectId], $childRoles);

        return in_array($permissionName, $childRoles);
    }

    /**
     * @param int $projectId
     * @param int|null $userId
     * @return bool
     */
    public function onConsideration(int $projectId, int $userId = null)
    {
        return $this->is(AuthAssignmentEntity::ROLE_ON_CONSIDERATION, $projectId, $userId);
    }

    /**
     * @param int $projectId
     * @param int|null $userId
     * @return bool
     */
    public function isBlocked(int $projectId, int $userId = null)
    {
        return $this->is(AuthAssignmentEntity::ROLE_BLOCKED, $projectId, $userId);
    }

    /**
     * @param int $projectId
     * @param int|null $userId
     * @return bool|mixed
     */
    public function isUser(int $projectId, int $userId = null)
    {
        return $this->is(AuthAssignmentEntity::ROLE_USER, $projectId, $userId);
    }

    /**
     * @param int $projectId
     * @param int|null $userId
     * @return bool|mixed
     */
    public function isManager(int $projectId, int $userId = null)
    {
        return $this->is(AuthAssignmentEntity::ROLE_MANAGER, $projectId, $userId);
    }

    /**
     * @param int $projectId
     * @param int|null $userId
     * @return bool
     */
    public function isProjectDirector(int $projectId, int $userId = null)
    {
        return $this->is(AuthAssignmentEntity::ROLE_PROJECT_DIRECTOR, $projectId, $userId);
    }

    /**
     * @param int $projectId
     * @param int|null $userId
     * @return bool
     */
    public function isCompanyDirector(int $projectId, int $userId = null)
    {
        return $this->is(AuthAssignmentEntity::ROLE_COMPANY_DIRECTOR, $projectId, $userId);
    }

    /**
     * @param int $projectId
     * @return \common\models\entities\ParticipantEntity|null
     */
    public function getParticipant(int $projectId)
    {
        return ParticipantRepository::instance()->findOne([
            'user_id' => Yii::$app->user->getId(),
            'project_id' => $projectId
        ]);
    }

    /**
     * @param int $projectId
     * @return int|null
     */
    public function getParticipantId(int $projectId)
    {
        $participant = $this->getParticipant($projectId);

        return ($participant) ? $participant->getId() : null;
    }

    /**
     * true, если участник удалился из проекта,
     * но перед этим был забанен
     *
     * @param int $projectId
     * @return bool
     */
    public function participantHadBlockedRole(int $projectId)
    {
        $participant = $this->getParticipant($projectId);

        if (!$participant) {
            return false;
        }

        return ($participant->hadBlockedRole() && $participant->getDeleted()) ? true : false;
    }
}