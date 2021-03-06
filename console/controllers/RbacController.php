<?php

namespace console\controllers;

use common\models\entities\AuthAssignmentEntity;
use yii\console\Controller;
use Yii;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $companyDirector = $auth->createRole(AuthAssignmentEntity::ROLE_COMPANY_DIRECTOR);
        $projectDirector = $auth->createRole(AuthAssignmentEntity::ROLE_PROJECT_DIRECTOR);
        $manager = $auth->createRole(AuthAssignmentEntity::ROLE_MANAGER);
        $user = $auth->createRole(AuthAssignmentEntity::ROLE_USER);
        $onConsideration = $auth->createRole(AuthAssignmentEntity::ROLE_ON_CONSIDERATION);
        $blocked = $auth->createRole(AuthAssignmentEntity::ROLE_BLOCKED);
        $deleted = $auth->createRole(AuthAssignmentEntity::ROLE_DELETED);

        $auth->add($companyDirector);
        $auth->add($projectDirector);
        $auth->add($manager);
        $auth->add($user);
        $auth->add($onConsideration);
        $auth->add($blocked);
        $auth->add($deleted);

        $addManagers = $auth->createPermission(AuthAssignmentEntity::PERMISSION_ADD_MANAGERS);
        $addProjectDirectors = $auth->createPermission(AuthAssignmentEntity::PERMISSION_ADD_PROJECT_DIRECTORS);

        $auth->add($addManagers);
        $auth->add($addProjectDirectors);

        $auth->addChild($manager, $user);
        $auth->addChild($projectDirector, $manager);
        $auth->addChild($projectDirector, $addManagers);
        $auth->addChild($companyDirector, $projectDirector);
        $auth->addChild($companyDirector, $addProjectDirectors);

        $this->stdout("\nRbac was init\n");
    }

    public function actionClear()
    {
        Yii::$app->authManager->removeAll();
    }
}