<?php

namespace common\components\helpers;

use common\models\activerecords\Participant;
use Yii;

class ParticipantHelper
{
    /**
     * @param Participant $participant
     * @return null|string
     */
    public static function getRoleAsString($participant)
    {
        $role = Yii::$app->authManager->getRolesByUser($participant->id);

        if(isset($role))
        {
            $key = key($role);
            return $role[$key]->name;
        }

        return null;
    }
}