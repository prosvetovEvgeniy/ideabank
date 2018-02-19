<?php

namespace common\components\facades;

use common\models\entities\CompanyEntity;
use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;
use common\models\repositories\company\CompanyRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\user\UserRepository;

/**
 * Class UserFacade
 * @package common\components\facades
 */
class UserFacade
{
    /**
     * @param UserEntity $user
     * @param CompanyEntity $company
     * @return UserEntity
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function signUpDirector(UserEntity $user, CompanyEntity $company)
    {
        $user = UserRepository::instance()->add($user);
        $company = CompanyRepository::instance()->add($company);

        $participant = new ParticipantEntity(
            $user->getId(), $company->getId()
        );

        $participant->setApproved(true);
        $participant->setApprovedAt(time());

        ParticipantRepository::instance()->add($participant);

        return $user;
    }
}