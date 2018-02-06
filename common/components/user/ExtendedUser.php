<?php

namespace common\components\user;

use common\models\repositories\ParticipantRepository;
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
     * @return bool
     */
    public function is(string $permissionName, int $projectId)
    {
        $cache = Yii::$app->cache;

        $key = [
            Yii::$app->user->identity->getUserId(),
            $projectId,
            $permissionName
        ];

        $cacheValue = $cache->get($key);

        if($cacheValue)
        {
            return $cacheValue;
        }

        $participant = ParticipantRepository::instance()->findOne([
            'user_id' => Yii::$app->user->identity->getUserId(),
            'project_id' => $projectId
        ]);

        if(!$participant)
        {
            return false;
        }

        $access = $this->getAccessChecker()->checkAccess($participant->getId(), $permissionName);

        Yii::$app->cache->set([
            Yii::$app->user->identity->getUserId(),
            $projectId,
            $permissionName
        ], $access);

        return $access;
    }
}