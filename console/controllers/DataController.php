<?php

namespace console\controllers;

use common\models\entities\AuthAssignmentEntity;
use yii\console\Controller;
use Yii;
use yii\base\Module;
use yii\db\Exception;
use yii\rbac\Role;

class DataController extends Controller
{
    private $db;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->db = Yii::$app->db;
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $companyIds = [];
        $projectIds = [];
        $userIds = [];
        $participantIds = [];
        $tasksIds = [];

        $commentsIds = [];
        $taskLikesIds = [];
        $commentLikeIds = [];
        $messageIds = [];


        //############### FILLING COMPANIES ###############


        $companyIds['infSysId'] = $this->addCompany('Совр. инф. системы');
        $companyIds['eCompanyId'] = $this->addCompany('E-company');


        //############### FILLING PROJECTS ###############


        $projectIds['vulcan'] = $this->addProject('Вулкан-М', $companyIds['infSysId'], 'Это проект Вулкан');
        $projectIds['github'] = $this->addProject('Github', $companyIds['eCompanyId'], 'Это проект Github');
        $projectIds['vk'] = $this->addProject('Вконтакте', $companyIds['eCompanyId'], 'Это проект Vk');
        $projectIds['xabr'] = $this->addProject('Хабрахабр', $companyIds['eCompanyId'], 'Это проект Xabr');


        //############### FILLING USERS ###############


        $userIds['evgeniy'] = $this->addUser('evgeniy','123456','evgeniy@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['newUser'] = $this->addUser('newUser','123456','newUser@mail.ru',
            '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['newLogin'] = $this->addUser('newLogin','123456','newLogin@mail.ru',
            '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['admin'] = $this->addUser('admin','123456','admin@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['edirector'] = $this->addUser('edirector','123456','edirector@mail.ru',
            '89131841102','edirector_first_name', 'edirector_second_name', 'edirector_last_name');

        $userIds['blockedUser'] = $this->addUser('blockedUser', '123456', 'blockedUser@mail.ru',
            '89131841102', 'blocked_user_first_name', 'blocked_user_second_name','blocked_user_last_name');

        $userIds['projectDirector'] = $this->addUser('projectDirector', '123456', 'projectDirector@mail.ru',
            '89131841102', 'projectDirector', 'projectDirector','projectDirector');

        $userIds['empryUser'] = $this->addUser('emptyUser', '123456', 'emptyUser@mail.ru',
            '89131841102', 'emptyUser', 'emptyUser','emptyUser');


        //############### FILLING PARTICIPANTS ###############


        $participantIds['evgeniyGithub'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['evgeniyVk'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['evgeniyXabr'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['xabr']);
        $participantIds['evgeniyVulcanm'] = $this->addParticipant($userIds['evgeniy'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['newLoginVulcan'] = $this->addParticipant($userIds['newLogin'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['newUserGithub'] = $this->addParticipant($userIds['newUser'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['newUserVulcan'] = $this->addParticipant($userIds['newUser'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['adminVulcan'] = $this->addParticipant($userIds['admin'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['edirectorGithub'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['edirectorVk'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['edirectorXabr'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['xabr']);
        $participantIds['edirectorVulcanConsideration'] = $this->addOnConsidirationParticipant($userIds['edirector'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['blockedUserGithub'] = $this->addBlockedParticipant($userIds['blockedUser'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['blockedUserVulcan'] = $this->addBlockedParticipant($userIds['blockedUser'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['projectDirectorVulcan'] = $this->addParticipant($userIds['projectDirector'], $companyIds['infSysId'], $projectIds['vulcan']);
        $participantIds['projectDirectorGit'] = $this->addParticipant($userIds['projectDirector'], $companyIds['eCompanyId'], $projectIds['github']);


        //############### FILLING AUTH ###############


        $blocked = $auth->getRole(AuthAssignmentEntity::ROLE_BLOCKED);
        $onConsideration = $auth->getRole(AuthAssignmentEntity::ROLE_ON_CONSIDERATION);
        $user = $auth->getRole(AuthAssignmentEntity::ROLE_USER);
        $manager = $auth->getRole(AuthAssignmentEntity::ROLE_MANAGER);
        $projectDirector = $auth->getRole(AuthAssignmentEntity::ROLE_PROJECT_DIRECTOR);
        $companyDirector = $auth->getRole(AuthAssignmentEntity::ROLE_COMPANY_DIRECTOR);


        //############### ASSIGNS ###############


        $this->assign($companyDirector, $participantIds['edirectorGithub'], $participantIds['edirectorGithub']);
        $this->assign($companyDirector, $participantIds['edirectorVk'], $participantIds['edirectorVk']);
        $this->assign($companyDirector, $participantIds['edirectorXabr'], $participantIds['edirectorXabr']);

        $this->assign($companyDirector, $participantIds['adminVulcan'], $participantIds['adminVulcan']);

        $this->assign($manager, $participantIds['evgeniyGithub'], $participantIds['edirectorGithub']);
        $this->assign($manager, $participantIds['evgeniyVulcanm'], $participantIds['adminVulcan']);
        $this->assign($user, $participantIds['evgeniyVk'], $participantIds['edirectorVk']);
        $this->assign($user, $participantIds['evgeniyXabr'], $participantIds['edirectorXabr']);

        $this->assign($onConsideration, $participantIds['edirectorVulcanConsideration'], $participantIds['adminVulcan']);

        $this->assign($user, $participantIds['newLoginVulcan'], $participantIds['adminVulcan']);

        $this->assign($user, $participantIds['newUserGithub'], $participantIds['evgeniyGithub']);
        $this->assign($user, $participantIds['newUserVulcan'], $participantIds['adminVulcan']);

        $this->assign($blocked, $participantIds['blockedUserGithub'], $participantIds['edirectorGithub']);
        $this->assign($blocked, $participantIds['blockedUserVulcan'], $participantIds['adminVulcan']);

        $this->assign($projectDirector, $participantIds['projectDirectorGit'], $participantIds['edirectorGithub']);
        $this->assign($projectDirector, $participantIds['projectDirectorVulcan'], $participantIds['adminVulcan']);


        //############### FILLING TASKS ###############


        $tasksIds['firstTask'] = $this->addTask('Первая задача git','Текст первой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['secondTask'] = $this->addTask('Вторая задача vk','Текст второй задачи', $userIds['evgeniy'], $projectIds['vk'], 0);
        $tasksIds['thirdTask'] = $this->addTask('Третья задача xabr','Текст третьей задачи', $userIds['evgeniy'], $projectIds['xabr'], 0);
        $tasksIds['fourthTask'] = $this->addTask('Вторая задача git','Текст второй задачи', $userIds['evgeniy'], $projectIds['github'], 1);
        $tasksIds['fifthTask'] = $this->addTask('Третья задача git','Текст третьей задачи', $userIds['evgeniy'], $projectIds['github'], 3, $tasksIds['fourthTask']);
        $tasksIds['sixthTask'] = $this->addTask('Четвертая задача git','Текст четвертой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['seventhTask'] = $this->addTask('Пятая задача git','Текст пятой задачи', $userIds['evgeniy'], $projectIds['github'], 3, $tasksIds['sixthTask']);
        $tasksIds['eightTask'] = $this->addTask('Шестая задача git','Текст шестой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['ninthTask'] = $this->addTask('Седьмая задача git','Текст седьмой задачи', $userIds['evgeniy'], $projectIds['github'], 2);
        $tasksIds['vulcanTask1'] = $this->addTask('New task for vulcan 1', 'New task for vulcan 1', $userIds['evgeniy'], $projectIds['vulcan'], 0);
        $tasksIds['vulcanTask2'] = $this->addTask('New task for vulcan 2', 'New task for vulcan 2', $userIds['newUser'], $projectIds['vulcan'], 1);
        $tasksIds['vulcanTask3'] = $this->addTask('New task for vulcan 3', 'New task for vulcan 3', $userIds['newLogin'], $projectIds['vulcan'], 2);
        $tasksIds['vulcanTask4'] = $this->addTask('New task for vulcan 4', 'New task for vulcan 4', $userIds['evgeniy'], $projectIds['vulcan'], 0);


        //############### FILLING COMMENTS ###############


        $commentsIds = $this->generateComments($tasksIds['firstTask'], $userIds['newUser'], 15);

        $commentsIds = $this->generateComments($tasksIds['vulcanTask1'], $userIds['evgeniy'], 12);

        $commentsIds = $this->generateComments($tasksIds['vulcanTask2'], $userIds['newUser'], 10);

        //############### FILLING TASKLIKES ###############


        $taskLikesIds = $this->generateTaskLikes($tasksIds, $userIds['evgeniy']);


        //############### FILLING COMMENTLIKES ###############


        $commentLikeIds = $this->generateCommentLikes($commentsIds, $userIds['evgeniy']);


        //############### FILLING MESSAGES ###############


        $this->generateMessages($userIds['evgeniy'], $userIds['edirector'],  1);
        $this->generateMessages($userIds['evgeniy'], $userIds['admin'], 1);
        $this->generateMessages($userIds['evgeniy'], $userIds['newLogin'], 1);

        //$this->generateMessages($userIds['newUser'], $userIds['newLogin'], 10);

        //$this->generateMessages($userIds['edirector'], $userIds['admin'], 10);
        //$this->generateMessages($userIds['edirector'], $userIds['newLogin'], 10);


        $this->stdout("\nTest data was init\n");
    }

    private function assign(Role $role, $participantId, $changerId = null)
    {
        Yii::$app->authManager->assign($role, $participantId);

        $changerId = $changerId ?? 'NULL';

        $this->db->createCommand("INSERT INTO auth_log (changeable_id, new_role_name, changer_id, created_at) VALUES 
                                ({$participantId}, '{$role->name}', {$changerId},{$this->getTime()})")->execute();
    }

    private function generateMessages(int $firstParticipantId, int $secondParticipantId, int $amount)
    {
        $time = $this->getTime();

        $dialog = "";

        for($i = 1; $i <= $amount; $i++)
        {
            $selfMessage = $i . ' message from id = ' . $firstParticipantId . ' to  id = ' . $secondParticipantId;
            $companionMessage = $i . ' message from id = ' . $secondParticipantId . ' to  id = ' . $firstParticipantId;

            if($i !== $amount)
            {
                $dialog .= "({$firstParticipantId}, {$secondParticipantId}, '{$selfMessage}', 'TRUE', 'FALSE', {$time}), ";
                $dialog .= "({$secondParticipantId}, {$firstParticipantId}, '{$selfMessage}', 'FALSE', 'FALSE', {$time}), ";

                $time += 10;

                $dialog .= "({$secondParticipantId}, {$firstParticipantId}, '{$companionMessage}', 'TRUE', 'FALSE', {$time}), ";
                $dialog .= "({$firstParticipantId}, {$secondParticipantId}, '{$companionMessage}', 'FALSE', 'FALSE', {$time}), ";

                $time += 10;
            }
            else
            {
                $dialog .= "({$firstParticipantId}, {$secondParticipantId}, '{$selfMessage}', 'TRUE', 'FALSE', {$time}), ";
                $dialog .= "({$secondParticipantId}, {$firstParticipantId}, '{$selfMessage}', 'FALSE', 'FALSE', {$time}), ";

                $time += 10;

                $dialog .= "({$secondParticipantId}, {$firstParticipantId}, '{$companionMessage}', 'TRUE', 'FALSE', {$time}), ";
                $dialog .= "({$firstParticipantId}, {$secondParticipantId}, '{$companionMessage}', 'FALSE', 'FALSE', {$time})";
            }
        }

        $this->db->createCommand("INSERT INTO message (self_id, companion_id, content, is_sender, viewed,created_at) VALUES {$dialog}")->execute();
    }

    private function generateTaskLikes(array $taskIds, int $userId, float $likeProbability = 0.5)
    {
        $taskLikeIds = [];

        $i = 0;

        foreach ($taskIds as $key => $taskId)
        {
            $currentProbability = rand(0,1000) / 1000;

            if($currentProbability <= $likeProbability)
            {
                $this->db->createCommand("INSERT INTO task_like (task_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$taskId}, {$userId}, TRUE , {$this->getTime()}, {$this->getTime()})")->execute();

                $taskLikeIds[$i . 'likeTo' . $key] = $this->db->getLastInsertID('task_like_id_seq');
            }
            else
            {
                $this->db->createCommand("INSERT INTO task_like (task_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$taskId}, {$userId}, FALSE , {$this->getTime()}, {$this->getTime()})")->execute();

                $taskLikeIds[$i . 'dislikeTo' . $key] = $this->db->getLastInsertID('task_like_id_seq');
            }

            $i++;
        }

        return $taskLikeIds;
    }

    private function generateCommentLikes(array $commentIds, int $userId, float $likeProbability = 0.5)
    {
        $commentLikeIds = [];

        $i = 0;

        foreach ($commentIds as $key => $commentId)
        {
            $currentProbability = rand(0,1000) / 1000;

            if($currentProbability <= $likeProbability)
            {
                $this->db->createCommand("INSERT INTO comment_like (comment_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$commentId}, {$userId}, TRUE , {$this->getTime()}, {$this->getTime()})")->execute();

                $commentLikeIds[$i . 'likeTo' . $key] = $this->db->getLastInsertID('comment_like_id_seq');
            }
            else
            {
                $this->db->createCommand("INSERT INTO comment_like (comment_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$commentId}, {$userId}, FALSE , {$this->getTime()}, {$this->getTime()})")->execute();

                $commentLikeIds[$i . 'dislikeTo' . $key] = $this->db->getLastInsertID('comment_like_id_seq');
            }

            $i++;
        }

        return $commentLikeIds;
    }

    private function generateComments($taskId, $senderId, $amount = 500)
    {
        $commentsIds = [];

        $lines[] = ['id' => 1, 'content' => '1 comment comment comment comment comment comment', 'parentId' => null];

        for ($i = 2; $i < $amount; $i++)
        {
            $probability = rand(0,1000)/1000;

            if($probability <= 0.35)
            {
                $lines[] = ['id' => $i, 'content' => $i . ' comment comment comment comment comment comment', 'parentId' => null];
            }
            else
            {
                $key = array_rand($lines, 1);

                $lines[] = ['id' => $i, 'content' => $i . ' comment comment comment comment comment comment', 'parentId' => $lines[$key]['id']];
            }
        }

        foreach ($lines as $line)
        {
            $parentId = $line['parentId'] ?? 'NULL';

            $this->db->createCommand("INSERT INTO comment (task_id, sender_id, content, parent_id, created_at, updated_at) VALUES 
                                      ({$taskId}, {$senderId}, '{$line['content']}', {$parentId},{$this->getTime()}, {$this->getTime()})")->execute();

            $commentsIds[$line['id'] . 'comment'] = $this->db->getLastInsertID('comment_id_seq');
        }

        return $commentsIds;
    }

    private function addCompany($name)
    {
        $this->db->createCommand("INSERT INTO company (name, created_at, updated_at) VALUES ('{$name}', {$this->getTime()}, {$this->getTime()})")->execute();
        return $this->db->getLastInsertID('company_id_seq');
    }

    private function addProject($name, $companyId, $description)
    {
        $this->db->createCommand("INSERT INTO project (name, company_id, description, created_at, updated_at)
                                      VALUES ('{$name}', {$companyId}, '{$description}', {$this->getTime()}, {$this->getTime()})")->execute();
        return $this->db->getLastInsertID('project_id_seq');
    }


    private function addUser($username, $password, $email, $phone, $firstName, $secondName, $lastName)
    {
        $password = Yii::$app->security->generatePasswordHash($password);

        $this->db->createCommand("INSERT INTO users (username, password, email, phone, first_name, second_name, last_name, created_at, updated_at)
                                       VALUES ('{$username}', '{$password}', '{$email}', '{$phone}', '{$firstName}', '{$secondName}', '{$lastName}',
                                               {$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('users_id_seq');
    }

    private function addTask($title, $content, $authorId, $projectId, $status, $parentId = null)
    {
        if($status === 3 && $parentId === null)
        {
            throw new Exception('Не указана родительская категория' . $title);
        }

        $parentId = $parentId ?? 'NULL';

        $plannedEndAt = $this->getTime() + 200000;

        $this->db->createCommand("INSERT INTO task (title, content, author_id, project_id, status, planned_end_at,parent_id, created_at, updated_at) VALUES
                                      ('{$title}', '{$content}', {$authorId}, {$projectId}, {$status},{$plannedEndAt}, {$parentId},{$this->getTime()}, {$this->getTime()})")
                                ->execute();

        return $this->db->getLastInsertID('task_id_seq');
    }

    public function addComment($taskId, $senderId, $content, $parentId = null)
    {
        $parentId = $parentId ?? 'NULL';
        $this->db->createCommand("INSERT INTO comment (task_id, sender_id, content, comment_id,created_at, updated_at) VALUES 
                                      ({$taskId}, {$senderId}, '{$content}', {$parentId},{$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('comment_id_seq');
    }

    public function addLikeToTask($taskId, $userId, $liked)
    {
        $liked = $liked ? 'true' : 'false';
        $this->db->createCommand("INSERT INTO task_like (task_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$taskId}, {$userId}, {$liked}, {$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('task_like_id_seq');
    }

    public function addLikeToComment($parentId, $userId, $liked)
    {
        $liked = $liked ? 'true' : 'false';
        $this->db->createCommand("INSERT INTO comment_like (comment_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$parentId}, {$userId}, {$liked}, {$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('comment_like_id_seq');
    }

    public function addMessage($selfId, $companionId, $isSender, $content)
    {
        $isSender = $isSender ? 'true' : 'false';
        $this->db->createCommand("INSERT INTO message (self_id, companion_id, content, is_sender, created_at) VALUES 
                                      ({$selfId}, {$companionId}, '{$content}', {$isSender}, {$this->getTime()})")
                                ->execute();

        return $this->db->getLastInsertID('message_id_seq');
    }

    public function addParticipantStub($userId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, created_at, updated_at) 
                                      VALUES ({$userId}, {$this->getTime()}, {$this->getTime()})")->execute();
        return $this->db->getLastInsertID('participant_id_seq');
    }

    public function addParticipant($userId, $companyId, $projectId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, project_id, approved, approved_at,created_at, updated_at) 
                                      VALUES ({$userId},{$companyId}, {$projectId}, TRUE, {$this->getTime()},{$this->getTime()}, {$this->getTime()})")
                                ->execute();

        return $this->db->getLastInsertID('participant_id_seq');
    }

    public function addBlockedParticipant($userId, $companyId, $projectId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, project_id, approved, approved_at,created_at, updated_at, blocked, blocked_at) 
                                      VALUES ({$userId},{$companyId}, {$projectId}, FALSE, {$this->getTime()},{$this->getTime()}, {$this->getTime()}, TRUE, {$this->getTime()})")
            ->execute();

        return $this->db->getLastInsertID('participant_id_seq');
    }

    public function addParticipantDirector($userId, $companyId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, approved, approved_at,created_at, updated_at) 
                                      VALUES ({$userId},{$companyId}, TRUE, {$this->getTime()},{$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('participant_id_seq');
    }

    public function addOnConsidirationParticipant($userId, $companyId, $projectId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, project_id, approved) 
                                      VALUES ({$userId},{$companyId}, {$projectId}, FALSE)")
            ->execute();

        return $this->db->getLastInsertID('participant_id_seq');
    }

    private function getTime()
    {
        return time();
    }
}