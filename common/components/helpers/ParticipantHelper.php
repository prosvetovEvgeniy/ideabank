<?php

namespace common\components\helpers;

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
            'user_id'    => Yii::$app->user->identity->getUserId(),
            'project_id' => $project->getId()
        ]);

        if (!$participant || $participant->getDeleted() || !$participant->getApproved() || $participant->getBlocked()) {
            return false;
        }

        return true;
    }
}