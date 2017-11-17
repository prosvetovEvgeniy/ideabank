<?php

namespace common\components\helpers;

use common\models\Participant;

class UserHelper
{
    public function getProjects($id)
    {
        $participants = Participant::find()->where(['user_id' => $id])
                                           ->andWhere(['is not', 'company_id', null])
                                           ->andWhere(['is not', 'project_id', null])
                                           ->andWhere(['approved' => true])
                                           ->all();

        $projects = [];

        foreach ($participants as $participant){
            $projects[] = $participant->project->name;
        }

        return $projects;
    }
}