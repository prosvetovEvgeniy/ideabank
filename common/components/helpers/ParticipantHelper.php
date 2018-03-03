<?php

namespace common\components\helpers;

use common\models\entities\ParticipantEntity;
use common\models\entities\ProjectEntity;
use common\models\repositories\participant\ParticipantRepository;
use Yii;

class ParticipantHelper
{
    /**
     * Проверяем является ли пользователем участником проекта
     *
     * @param ProjectEntity $project
     * @return bool
     */
    public static function checkOnParticipantInProject(ProjectEntity $project)
    {
        $participant = ParticipantRepository::instance()->findOne([
            'user_id'    => Yii::$app->user->getId(),
            'project_id' => $project->getId()
        ]);

        if (!$participant ||
            $participant->getDeleted() ||
            !$participant->getApproved() ||
            $participant->getBlocked())
        {
            return false;
        }

        return true;
    }

    /**
     * @param ParticipantEntity $participant
     * @return bool
     */
    public static function addOrUpdateRoleCache(ParticipantEntity $participant)
    {
        $cache = Yii::$app->cache;

        $key = [
            $participant->getUserId(),
            $participant->getProjectId()
        ];

        $childRoles = array_keys(
            Yii::$app->authManager->getChildRoles(
                $participant->getAuthAssignment()->getRoleName()
            )
        );

        if ($cache->get($key) !== false) {
            return $cache->set($key, $childRoles);
        }

        return $cache->add($key, $childRoles);
    }

    /**
     * Проверяет разрешение на то, может ли participant
     * изменять роль у другого participant
     *
     * @param ParticipantEntity $whoChange
     * @param ParticipantEntity $changeable
     * @return bool
     */
    public static function canChangeRole(ParticipantEntity $whoChange, ParticipantEntity $changeable)
    {
//        $auth = Yii::$app->authManager;
//
//
//
//        if () {
//
//        }

        return true;
    }
}