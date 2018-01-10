<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use yii\base\Module;
use yii\db\Exception;

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
        /*$this->db->createCommand("TRUNCATE company CASCADE")->execute();
        $this->db->createCommand("TRUNCATE project CASCADE")->execute();
        $this->db->createCommand("TRUNCATE users CASCADE")->execute();
        $this->db->createCommand("TRUNCATE participant CASCADE")->execute();
        $this->db->createCommand("TRUNCATE auth_assignment CASCADE")->execute();
        $this->db->createCommand("TRUNCATE task CASCADE")->execute();
        $this->db->createCommand("TRUNCATE comment CASCADE")->execute();
        $this->db->createCommand("TRUNCATE task_like CASCADE")->execute();
        $this->db->createCommand("TRUNCATE comment_like CASCADE")->execute();
        $this->db->createCommand("TRUNCATE message CASCADE")->execute();
        $this->db->createCommand("TRUNCATE notice CASCADE")->execute();*/


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
        $noticeIds = [];


        //############### FILLING COMPANIES ###############


        $companyIds['infSysId'] = $this->addCompany('Совр. инф. системы');
        $companyIds['eCompanyId'] = $this->addCompany('E-company');


        //############### FILLING PROJECTS ###############


        $projectIds['vulcan'] = $this->addProject('Вулкан-М', $companyIds['infSysId']);
        $projectIds['github'] = $this->addProject('Github', $companyIds['eCompanyId']);
        $projectIds['vk'] = $this->addProject('Вконтакте', $companyIds['eCompanyId']);
        $projectIds['xabr'] = $this->addProject('Хабрахабр', $companyIds['eCompanyId']);


        //############### FILLING USERS ###############


        $userIds['evgeniy'] = $this->addUser('evgeniy','123456','evgeniy@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['admin'] = $this->addUser('admin','123456','admin@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['edirector'] = $this->addUser('edirector','123456','edirector@mail.ru',
            '89131841102','edirector_first_name', 'edirector_second_name', 'edirector_last_name');


        //############### FILLING PARTICIPANTS ###############


        $participantIds['evgeniyStub'] = $this->addParticipantStub($userIds['evgeniy']);
        $participantIds['evgeniyGithub'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['evgeniyVk'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['evgeniyXabr'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['xabr']);
        $participantIds['evgeniyVulcanm'] = $this->addParticipant($userIds['evgeniy'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['adminStub'] = $this->addParticipantStub($userIds['admin']);
        $participantIds['adminDirector'] = $this->addParticipantDirector($userIds['admin'], $companyIds['infSysId']);
        $participantIds['adminVulcan'] = $this->addParticipant($userIds['admin'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['edirectorStub'] = $this->addParticipantStub($userIds['edirector']);
        $participantIds['edirectorDirector'] = $this->addParticipantDirector($userIds['edirector'], $companyIds['eCompanyId']);
        $participantIds['edirectorGithub'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['edirectorVk'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['edirectorXabr'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['xabr']);


        //############### FILLING AUTH ###############


        $user = $auth->getRole('user');
        $manager = $auth->getRole('manager');
        $director = $auth->getRole('projectDirector');

        $auth->assign($user,$participantIds['evgeniyGithub']);
        $auth->assign($user,$participantIds['evgeniyVk']);
        $auth->assign($user,$participantIds['evgeniyXabr']);
        $auth->assign($manager, $participantIds['evgeniyVulcanm']);

        $auth->assign($director,$participantIds['adminVulcan']);

        $auth->assign($director,$participantIds['edirectorGithub']);
        $auth->assign($director,$participantIds['edirectorVk']);
        $auth->assign($director,$participantIds['edirectorXabr']);


        //############### FILLING TASKS ###############


        $tasksIds['firstTask'] = $this->addTask('Первая задача','Текст первой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['secondTask'] = $this->addTask('Вторая задача','Текст второй задачи', $userIds['evgeniy'], $projectIds['vk'], 0);
        $tasksIds['thirdTask'] = $this->addTask('Третья задача','Текст третьей задачи', $userIds['evgeniy'], $projectIds['xabr'], 0);
        $tasksIds['fourthTask'] = $this->addTask('Вторая задача','Текст второй задачи', $userIds['evgeniy'], $projectIds['github'], 1);
        $tasksIds['fifthTask'] = $this->addTask('Третья задача','Текст третьей задачи', $userIds['evgeniy'], $projectIds['github'], 3, $tasksIds['fourthTask']);
        $tasksIds['sixthTask'] = $this->addTask('Четвертая задача','Текст четвертой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['seventhTask'] = $this->addTask('Пятая задача','Текст пятой задачи', $userIds['evgeniy'], $projectIds['github'], 3, $tasksIds['sixthTask']);
        $tasksIds['eightTask'] = $this->addTask('Шестая задача','Текст шестой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['ninthTask'] = $this->addTask('Седьмая задача','Текст седьмой задачи', $userIds['evgeniy'], $projectIds['github'], 2);


        //############### FILLING COMMENTS ###############


        $commentsIds = $this->generateComments($tasksIds['firstTask'], $userIds['evgeniy'], 100);


        //############### FILLING TASKLIKES ###############


        $taskLikesIds = $this->generateTaskLikes($tasksIds, $userIds['evgeniy']);


        //############### FILLING COMMENTLIKES ###############


        $commentLikeIds = $this->generateCommentLikes($commentsIds, $userIds['evgeniy']);


        //############### FILLING MESSAGES ###############


        $this->generateMessages($userIds['evgeniy'], $userIds['edirector'], 20);
        $this->generateMessages($userIds['evgeniy'], $userIds['admin'], 20);


        //############### FILLING NOTICES ###############


        $noticeIds['firstNoticeToEvgeniy'] = $this->addNotice($userIds['evgeniy'], 'Первая заметка', false);
        $noticeIds['secondNoticeToEvgeniy'] = $this->addNotice($userIds['evgeniy'], 'Вторая заметка', true);
        $noticeIds['thirdNoticeToEvgeniy'] = $this->addNotice($userIds['evgeniy'], 'Третья заметка', false);

        $this->stdout("\nTest data was init\n");
    }

    private function generateMessages(int $firstParticipantId, int $secondParticipantId, int $amount)
    {

        $time = $this->getTime();

        for($i = 1; $i <= $amount; $i++)
        {
            $selfMessage = $i . ' message from id = ' . $firstParticipantId . ' to  id = ' . $secondParticipantId;
            $companionMessage = $i . ' message from id = ' . $secondParticipantId . ' to  id = ' . $firstParticipantId;

            $this->db->createCommand("INSERT INTO message (self_id, companion_id, content, is_sender, created_at) VALUES
                                    ({$firstParticipantId}, {$secondParticipantId}, '{$selfMessage}', 'TRUE', {$time})")->execute();
            $this->db->createCommand("INSERT INTO message (self_id, companion_id, content, is_sender, created_at) VALUES
                                    ({$secondParticipantId}, {$firstParticipantId}, '{$selfMessage}', 'FALSE', {$time})")->execute();

            $time += 10;

            $this->db->createCommand("INSERT INTO message (self_id, companion_id, content, is_sender, created_at) VALUES
                                    ({$secondParticipantId}, {$firstParticipantId}, '{$companionMessage}', 'TRUE', {$time})")->execute();
            $this->db->createCommand("INSERT INTO message (self_id, companion_id, content, is_sender, created_at) VALUES
                                    ({$firstParticipantId}, {$secondParticipantId}, '{$companionMessage}', 'FALSE', {$time})")->execute();

            $time += 10;
        }
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

    private function addProject($name, $companyId)
    {
        $this->db->createCommand("INSERT INTO project (name, company_id, created_at, updated_at)
                                      VALUES ('{$name}', {$companyId}, {$this->getTime()}, {$this->getTime()})")->execute();
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

    public function addNotice($recipientId, $content, $viewed)
    {
        $viewed = $viewed ? 'true' : 'false';
        $this->db->createCommand("INSERT INTO notice (recipient_id, content, viewed,created_at) VALUES 
                                      ({$recipientId}, '{$content}', {$viewed} ,{$this->getTime()})")->execute();

        return $this->db->getLastInsertID('notice_id_seq');
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

    public function addParticipantDirector($userId, $companyId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, approved, approved_at,created_at, updated_at) 
                                      VALUES ({$userId},{$companyId}, TRUE, {$this->getTime()},{$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('participant_id_seq');
    }

    
    private function getTime()
    {
        return time();
    }
}