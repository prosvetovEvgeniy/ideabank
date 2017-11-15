<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $director = $auth->createRole('director');
        $manager = $auth->createRole('manager');
        $user = $auth->createRole('user');

        $auth->add($director);
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
        $auth->addChild($director, $manager);
        $auth->addChild($director, $addUsers);
        $auth->addChild($director, $useRbac);

        $this->stdout("Done!\n");
    }

    public function actionClear()
    {
        Yii::$app->authManager->removeAll();
    }
}