<?php

namespace common\components\helpers;

use common\models\entities\ParticipantEntity;
use common\models\entities\ProjectEntity;
use common\models\repositories\participant\ParticipantRepository;
use Yii;

class ParticipantHelper
{
    /**
     * @return ParticipantHelper
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Проверяем является ли пользователем участником проекта
     *
     * @param ProjectEntity $project
     * @return bool
     */
    public function checkOnParticipantInProject(ProjectEntity $project)
    {
        $participant = ParticipantRepository::instance()->findOne([
            'user_id'    => Yii::$app->user->getId(),
            'project_id' => $project->getId()
        ]);

        if (!$participant || $participant->getDeleted() || !$participant->getApproved() || $participant->getBlocked()) {
            return false;
        }

        return true;
    }

    /**
     * @param ParticipantEntity $participant
     * @return bool
     */
    public function addOrUpdateRoleCache(ParticipantEntity $participant)
    {
//        $cache = Yii::$app->cache;
//
//        $key = [
//            $participant->getUserId(),
//            $participant->getProjectId(),
//            $participant->getRoleName()
//        ];
//
//        $cacheVal = $cache->get($key);
//
//        if ($cacheVal !== false) {
//            //изменить
//            if (!$cache->set($key, (int) !$cacheVal)) {
//                return false;
//            }
//        } else {
//            //добавить
//            if (!$cache->add($key, 1)) {
//                return false;
//            }
//        }
//
//        if ($participant->hasPreviousAuthLog()) {
//            //имеется
//            $key = [
//                $participant->getUserId(),
//                $participant->getProjectId(),
//                $participant->getPreviousAuthLog()->getRoleName()
//            ];
//        }

        return true;
    }
}