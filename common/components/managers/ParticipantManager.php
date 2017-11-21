<?php

namespace common\components\managers;

use common\models\activerecords\Company;
use common\models\activerecords\Participant;
use common\models\activerecords\Users;
use Yii;
use yii\db\Exception;

class ParticipantManager
{
    /**
     * @param Users $user
     * @return Participant
     * @throws Exception
     */
    public function attachUser($user)
    {
        $participant = new Participant();
        $participant->user_id = $user->id;

        if(!$participant->save())
        {
            throw new Exception('Cannot save participant to the database');
        }

        return $participant;
    }

    /**
     * @param Users $user
     * @param Company $company
     * @return Participant
     * @throws Exception
     */
    public function attachDirector($user, $company)
    {
        $participantDirector = new Participant();
        $participantDirector->user_id = $user->id;
        $participantDirector->company_id = $company->id;

        if(!$participantDirector->save())
        {
            throw new Exception('Cannot save paricipant(director) to the database');
        }

        $auth = Yii::$app->authManager;
        $companyDirector = $auth->getRole('companyDirector');
        $auth->assign($companyDirector, $participantDirector->id);

        $participantStub = new Participant();
        $participantStub->user_id = $user->id;

        if(!$participantStub->save())
        {
            throw new Exception('Cannot save participant(stub) to the database');
        }

        return $participantStub;
    }
}