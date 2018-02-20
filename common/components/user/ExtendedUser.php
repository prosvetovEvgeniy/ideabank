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
     * @return bool|mixed
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

        if($cacheValue) {
            return $cacheValue;
        }

        $participant = ParticipantRepository::instance()->findOne([
            'user_id' => $userId,
            'project_id' => $projectId
        ]);

        if (!$participant) {
            return false;
        }

        $access = $this->getAccessChecker()->checkAccess($participant->getId(), $permissionName);

        Yii::$app->cache->set([
            $userId,
            $projectId,
            $permissionName
        ], $access);

        return $access;
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
}