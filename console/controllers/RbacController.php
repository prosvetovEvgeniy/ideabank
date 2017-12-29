<?php

namespace console\controllers;

use common\models\activerecords\Participant;
use common\models\entities\ParticipantEntity;
use yii\console\Controller;
use Yii;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $companyDirector = $auth->createRole(ParticipantEntity::ROLE_COMPANY_DIRECTOR);
        $projectDirector = $auth->createRole(ParticipantEntity::ROLE_PROJECT_DIRECTOR);
        $manager = $auth->createRole(ParticipantEntity::ROLE_MANAGER);
        $user = $auth->createRole(ParticipantEntity::ROLE_USER);

        $auth->add($companyDirector);
        $auth->add($projectDirector);
        $auth->add($manager);
        $auth->add($user);

        $viewProfile = $auth->createPermission('viewProfile');
        $viewProfile->description = 'Просматривать профиль';

        $createTask = $auth->createPermission('createTask');
        $createTask->description = 'Создавать задачи';

        $commentTasks = $auth->createPermission('commentTasks');
        $commentTasks->description = 'Комментировать задачи';

        $sendMessages = $auth->createPermission('sendMessages');
        $sendMessages->description = 'Отправлять сообщения';

        $moderateTask = $auth->createPermission('moderateTask');
        $moderateTask->description = 'Редактировать предложения';

        $moderateComments = $auth->createPermission('moderateComments');
        $moderateComments->description = 'Редактировать комментарии';

        $joinTask = $auth->createPermission('joinTask');
        $joinTask->description = 'Объединять задачи';

        $changeStatus = $auth->createPermission('changeStatus');
        $changeStatus->description = 'Изменять статус задачи';

        $usePrivateComments = $auth->createPermission('usePrivateComments');
        $usePrivateComments->description = 'Использовать приватные комментарии';

        $sendNotice = $auth->createPermission('sendNotice');
        $sendNotice->description = 'Отправлять уведомления';

        $blockUsers = $auth->createPermission('blockUsers');
        $blockUsers->description = 'Блокировать пользователей';

        $addUsers = $auth->createPermission('addUsers');
        $addUsers->description = 'Добавлять пользователей';

        $useRbac = $auth->createPermission('useRbac');
        $useRbac->description = 'Использовать систему управления ролями';

        $createProjects = $auth->createPermission('createProjects');
        $createProjects->description = 'Создавать проекты';

        $addProjectDirectors = $auth->createPermission('addProjectDirectors');
        $addProjectDirectors->description = 'Добавлять руководителей проекта в проекты';

        $auth->add($createTask);
        $auth->add($viewProfile);
        $auth->add($sendMessages);
        $auth->add($commentTasks);
        $auth->add($moderateTask);
        $auth->add($moderateComments);
        $auth->add($joinTask);
        $auth->add($changeStatus);
        $auth->add($usePrivateComments);
        $auth->add($sendNotice);
        $auth->add($blockUsers);
        $auth->add($addUsers);
        $auth->add($useRbac);
        $auth->add($createProjects);
        $auth->add($addProjectDirectors);

        $auth->addChild($user, $viewProfile);
        $auth->addChild($user, $createTask);
        $auth->addChild($user, $commentTasks);
        $auth->addChild($user, $sendMessages);
        $auth->addChild($manager, $user);
        $auth->addChild($manager, $moderateTask);
        $auth->addChild($manager, $moderateComments);
        $auth->addChild($manager, $joinTask);
        $auth->addChild($manager, $changeStatus);
        $auth->addChild($manager, $usePrivateComments);
        $auth->addChild($manager, $sendNotice);
        $auth->addChild($manager, $blockUsers);
        $auth->addChild($projectDirector, $manager);
        $auth->addChild($projectDirector, $addUsers);
        $auth->addChild($projectDirector, $useRbac);
        $auth->addChild($companyDirector, $projectDirector);
        $auth->addChild($companyDirector, $createProjects);
        $auth->addChild($companyDirector, $addProjectDirectors);

        $this->stdout("\nRbac was init\n");

    }

    public function actionClear()
    {
        Yii::$app->authManager->removeAll();
    }
}