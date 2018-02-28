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
        Yii::$app->db->createCommand("TRUNCATE TABLE users CASCADE")->execute();
        Yii::$app->db->createCommand("TRUNCATE TABLE company CASCADE")->execute();

        Yii::$app->db->createCommand("alter sequence users_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence task_notice_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence task_like_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence task_file_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence task_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence project_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence participant_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence notice_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence message_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence company_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence comment_like_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence comment_notice_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence comment_id_seq restart;")->execute();
        Yii::$app->db->createCommand("alter sequence auth_log_id_seq restart;")->execute();

        $this->stdout("\ntables were truncated\n");

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

        $this->stdout("\ncompanies were filled\n");

        //############### FILLING PROJECTS ###############


        $projectIds['vulcan'] = $this->addProject('Вулкан-М', $companyIds['infSysId'], 'Это проект Вулкан');
        $projectIds['github'] = $this->addProject('Github', $companyIds['eCompanyId'], 'Это проект Github');
        $projectIds['vk'] = $this->addProject('Вконтакте', $companyIds['eCompanyId'], 'Это проект Vk');
        $projectIds['xabr'] = $this->addProject('Хабрахабр', $companyIds['eCompanyId'], 'Это проект Xabr');

        $this->stdout("projects were filled\n");

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

        $this->stdout("users were filled\n");

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

        $this->stdout("participants were filled\n");

        //############### FILLING AUTH ###############


        $blocked = $auth->getRole(AuthAssignmentEntity::ROLE_BLOCKED);
        $onConsideration = $auth->getRole(AuthAssignmentEntity::ROLE_ON_CONSIDERATION);
        $user = $auth->getRole(AuthAssignmentEntity::ROLE_USER);
        $manager = $auth->getRole(AuthAssignmentEntity::ROLE_MANAGER);
        $projectDirector = $auth->getRole(AuthAssignmentEntity::ROLE_PROJECT_DIRECTOR);
        $companyDirector = $auth->getRole(AuthAssignmentEntity::ROLE_COMPANY_DIRECTOR);


        $this->stdout("auth was filled\n");

        //############### ASSIGNS ###############


        $this->addLog($companyDirector, $participantIds['edirectorGithub'], $participantIds['edirectorGithub']);
        $this->addLog($companyDirector, $participantIds['edirectorVk'], $participantIds['edirectorVk']);
        $this->addLog($companyDirector, $participantIds['edirectorXabr'], $participantIds['edirectorXabr']);
            $auth->assign($companyDirector, $participantIds['edirectorGithub']);
            $auth->assign($companyDirector, $participantIds['edirectorVk']);
            $auth->assign($companyDirector, $participantIds['edirectorXabr']);

        $this->addLog($companyDirector, $participantIds['adminVulcan'], $participantIds['adminVulcan']);
            $auth->assign($companyDirector, $participantIds['adminVulcan']);

        $this->addLog($onConsideration, $participantIds['evgeniyGithub'], $participantIds['edirectorGithub']);
        $this->addLog($onConsideration, $participantIds['evgeniyVulcanm'], $participantIds['evgeniyVulcanm']);
        $this->addLog($onConsideration, $participantIds['evgeniyVk'], $participantIds['evgeniyVk']);
        $this->addLog($onConsideration, $participantIds['evgeniyXabr'], $participantIds['evgeniyXabr']);
        $this->addLog($user, $participantIds['evgeniyGithub'], $participantIds['edirectorGithub']);
        $this->addLog($user, $participantIds['evgeniyVulcanm'], $participantIds['evgeniyVulcanm']);
        $this->addLog($user, $participantIds['evgeniyVk'], $participantIds['edirectorVk']);
        $this->addLog($user, $participantIds['evgeniyXabr'], $participantIds['edirectorXabr']);
        $this->addLog($manager, $participantIds['evgeniyGithub'], $participantIds['edirectorGithub']);
        $this->addLog($manager, $participantIds['evgeniyVulcanm'], $participantIds['adminVulcan']);
            $auth->assign($user, $participantIds['evgeniyXabr']);
            $auth->assign($user, $participantIds['evgeniyVk']);
            $auth->assign($manager, $participantIds['evgeniyGithub']);
            $auth->assign($manager, $participantIds['evgeniyVulcanm']);

        $this->addLog($onConsideration, $participantIds['edirectorVulcanConsideration'], $participantIds['adminVulcan']);
            $auth->assign($onConsideration, $participantIds['edirectorVulcanConsideration']);

        $this->addLog($onConsideration, $participantIds['newLoginVulcan'], $participantIds['newLoginVulcan']);
        $this->addLog($user, $participantIds['newLoginVulcan'], $participantIds['adminVulcan']);
            $auth->assign($user, $participantIds['newLoginVulcan']);

        $this->addLog($onConsideration, $participantIds['newUserGithub'], $participantIds['newUserGithub']);
        $this->addLog($onConsideration, $participantIds['newUserVulcan'], $participantIds['newUserVulcan']);
        $this->addLog($user, $participantIds['newUserGithub'], $participantIds['evgeniyGithub']);
        $this->addLog($user, $participantIds['newUserVulcan'], $participantIds['adminVulcan']);
            $auth->assign($user, $participantIds['newUserGithub']);
            $auth->assign($user, $participantIds['newUserVulcan']);


        $this->addLog($onConsideration, $participantIds['blockedUserGithub'], $participantIds['blockedUserGithub']);
        $this->addLog($onConsideration, $participantIds['blockedUserVulcan'], $participantIds['blockedUserVulcan']);
        $this->addLog($user, $participantIds['blockedUserGithub'], $participantIds['edirectorGithub']);
        $this->addLog($user, $participantIds['blockedUserVulcan'], $participantIds['adminVulcan']);
        $this->addLog($blocked, $participantIds['blockedUserGithub'], $participantIds['edirectorGithub']);
        $this->addLog($blocked, $participantIds['blockedUserVulcan'], $participantIds['adminVulcan']);
            $auth->assign($blocked, $participantIds['blockedUserGithub']);
            $auth->assign($blocked, $participantIds['blockedUserVulcan']);


        $this->addLog($onConsideration, $participantIds['projectDirectorGit'], $participantIds['projectDirectorGit']);
        $this->addLog($onConsideration, $participantIds['projectDirectorVulcan'], $participantIds['projectDirectorVulcan']);
        $this->addLog($user, $participantIds['projectDirectorGit'], $participantIds['edirectorGithub']);
        $this->addLog($user, $participantIds['projectDirectorVulcan'], $participantIds['adminVulcan']);
        $this->addLog($projectDirector, $participantIds['projectDirectorGit'], $participantIds['edirectorGithub']);
        $this->addLog($projectDirector, $participantIds['projectDirectorVulcan'], $participantIds['adminVulcan']);
            $auth->assign($projectDirector, $participantIds['projectDirectorGit']);
            $auth->assign($projectDirector, $participantIds['projectDirectorVulcan']);

        $this->stdout("assigns were filled\n");

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

        $this->stdout("tasks were filled\n");

        //############### FILLING COMMENTS ###############


        $commentsIds = $this->generateComments($tasksIds['firstTask'], $userIds['newUser'], 15);

        $commentsIds = $this->generateComments($tasksIds['vulcanTask1'], $userIds['evgeniy'], 12);

        $commentsIds = $this->generateComments($tasksIds['vulcanTask2'], $userIds['newUser'], 10);

        $this->stdout("comments were filled\n");

        //############### FILLING TASKLIKES ###############


        $taskLikesIds = $this->generateTaskLikes($tasksIds, $userIds['evgeniy']);

        $this->stdout("task_likes were filled\n");

        //############### FILLING COMMENTLIKES ###############


        $commentLikeIds = $this->generateCommentLikes($commentsIds, $userIds['evgeniy']);

        $this->stdout("comments were filled\n");

        //############### FILLING MESSAGES ###############


        $this->generateMessages($userIds['evgeniy'], $userIds['edirector'],  1);
        $this->generateMessages($userIds['evgeniy'], $userIds['admin'], 1);
        $this->generateMessages($userIds['evgeniy'], $userIds['newLogin'], 1);

        //$this->generateMessages($userIds['newUser'], $userIds['newLogin'], 10);

        //$this->generateMessages($userIds['edirector'], $userIds['admin'], 10);
        //$this->generateMessages($userIds['edirector'], $userIds['newLogin'], 10);

        $this->stdout("messages were filled\n");

        $this->stdout("\nTest data was init\n");
    }

    private function addLog(Role $role, $changeableId, $changerId = null)
    {
        //Yii::$app->authManager->assign($role, $participantId);

        $changerId = $changerId ?? 'NULL';

        $this->db->createCommand("INSERT INTO auth_log (changeable_id, role_name, changer_id, created_at) VALUES 
                                ({$changeableId}, '{$role->name}', {$changerId},{$this->getTime()})")->execute();
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