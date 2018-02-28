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
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $userId = $userId ?? Yii::$app->user->getId();

        $cache = Yii::$app->cache;

        $key = [
            $userId,
            $projectId,
            $permissionName
        ];

        $cacheValue = $cache->get($key);

        if ($cacheValue !== false) {
            return (bool) $cacheValue;
        }

        $participant = ParticipantRepository::instance()->findOne([
            'user_id' => $userId,
            'project_id' => $projectId
        ]);

        if (!$participant) {
            return false;
        }

        /**
         * @var bool $access
         */
        $access = $this->getAccessChecker()->checkAccess($participant->getId(), $permissionName);

        Yii::$app->cache->set([
            $userId,
            $projectId,
            $permissionName
        ], (int) $access);

        return $access;
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
     * @return int
     */
    public function getParticipantId(int $projectId)
    {
        $participant = ParticipantRepository::instance()->findOne([
           'user_id' => Yii::$app->user->getId(),
           'project_id' => $projectId
        ]);

        return $participant->getId();
    }
}