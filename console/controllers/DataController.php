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


        $companyIds['infSysId'] = $this->addCompany('Современные информационные системы');
        $companyIds['eCompanyId'] = $this->addCompany('E-company');

        $projectIds['vulcan'] = $this->addProject('Вулкан-М', $companyIds['infSysId']);
        $projectIds['github'] = $this->addProject('Github', $companyIds['eCompanyId']);
        $projectIds['vk'] = $this->addProject('Вконтакте', $companyIds['eCompanyId']);
        $projectIds['xabr'] = $this->addProject('Хабрахабр', $companyIds['eCompanyId']);

        $userIds['evgeniy'] = $this->addUser('evgeniy95','123456','evgeniy@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['admin'] = $this->addUser('admin','123456','admin@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');

        $userIds['edirector'] = $this->addUser('edirector','123456','edirector@mail.ru',
            '89131841102','edirector_first_name', 'edirector_second_name', 'edirector_last_name');

        $participantIds['evgeniyStub'] = $this->addParticipantStub($userIds['evgeniy']);
        $participantIds['evgeniyGithub'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['evgeniyVk'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['evgeniyXabr'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['xabr']);

        $participantIds['adminStub'] = $this->addParticipantStub($userIds['admin']);
        $participantIds['adminDirector'] = $this->addParticipantDirector($userIds['admin'], $companyIds['infSysId']);
        $participantIds['adminVulcan'] = $this->addParticipant($userIds['admin'], $companyIds['infSysId'], $projectIds['vulcan']);

        $participantIds['edirectorStub'] = $this->addParticipantStub($userIds['edirector']);
        $participantIds['edirectorDirector'] = $this->addParticipantDirector($userIds['edirector'], $companyIds['eCompanyId']);
        $participantIds['edirectorGithub'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['edirectorVk'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['edirectorXabr'] = $this->addParticipant($userIds['edirector'], $companyIds['eCompanyId'], $projectIds['xabr']);

        $user = $auth->getRole('user');
        $director = $auth->getRole('projectDirector');

        $auth->assign($user,$participantIds['evgeniyGithub']);
        $auth->assign($user,$participantIds['evgeniyVk']);
        $auth->assign($user,$participantIds['evgeniyXabr']);

        $auth->assign($director,$participantIds['adminVulcan']);

        $auth->assign($director,$participantIds['edirectorGithub']);
        $auth->assign($director,$participantIds['edirectorVk']);
        $auth->assign($director,$participantIds['edirectorXabr']);

        $tasksIds['firstTask'] = $this->addTask('Первая задача','Текст первой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['secondTask'] = $this->addTask('Вторая задача','Текст второй задачи', $userIds['evgeniy'], $projectIds['vk'], 0);
        $tasksIds['thirdTask'] = $this->addTask('Третья задача','Текст третьей задачи', $userIds['evgeniy'], $projectIds['xabr'], 0);
        $tasksIds['fourthTask'] = $this->addTask('Вторая задача','Текст второй задачи', $userIds['evgeniy'], $projectIds['github'], 1);
        $tasksIds['fifthTask'] = $this->addTask('Третья задача','Текст третьей задачи', $userIds['evgeniy'], $projectIds['github'], 3, $tasksIds['fourthTask']);
        $tasksIds['sixthTask'] = $this->addTask('Четвертая задача','Текст четвертой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['seventhTask'] = $this->addTask('Пятая задача','Текст пятой задачи', $userIds['evgeniy'], $projectIds['github'], 3, $tasksIds['sixthTask']);
        $tasksIds['eightTask'] = $this->addTask('Шестая задача','Текст шестой задачи', $userIds['evgeniy'], $projectIds['github'], 0);
        $tasksIds['ninthTask'] = $this->addTask('Седьмая задача','Текст седьмой задачи', $userIds['evgeniy'], $projectIds['github'], 2);


        $commentsIds['firstComment'] = $this->addComment($tasksIds['firstTask'], $userIds['evgeniy'],'Первый комментарий');
        $commentsIds['secondComment'] = $this->addComment($tasksIds['secondTask'], $userIds['evgeniy'],'Второй комментарий', $commentsIds['firstComment']);
        $commentsIds['thirdComment'] = $this->addComment($tasksIds['thirdTask'], $userIds['evgeniy'],'Третьи комментарий');

        $taskLikesIds['firstLikeToFirstTask'] = $this->addLikeToTask($tasksIds['firstTask'], $userIds['evgeniy'], true);
        $taskLikesIds['firstLikeToSecondTask'] = $this->addLikeToTask($tasksIds['secondTask'], $userIds['evgeniy'], true);
        $taskLikesIds['firstDislikeToThirdTask'] = $this->addLikeToTask($tasksIds['thirdTask'], $userIds['evgeniy'], false);

        $commentLikeIds['firstLikeToFirstComment'] = $this->addLikeToComment($commentsIds['firstComment'], $userIds['evgeniy'], true);
        $commentLikeIds['firstLikeToSecondComment'] = $this->addLikeToComment($commentsIds['secondComment'], $userIds['evgeniy'], true);
        $commentLikeIds['firstDislikeToThirdComment'] = $this->addLikeToComment($commentsIds['secondComment'], $userIds['evgeniy'], false);

        $messageIds['fromEvgeniyToEdirector'] = $this->addMessage($userIds['evgeniy'], $userIds['edirector'],true,'Привет');
        $messageIds['fromEdirectorToEvgeniy'] = $this->addMessage($userIds['edirector'], $userIds['evgeniy'],false,'Привет');
        $messageIds['fromEdirectorToEvgeniy'] = $this->addMessage($userIds['edirector'], $userIds['evgeniy'],true,'Пока');
        $messageIds['fromEvgeniyToEdirector'] = $this->addMessage($userIds['evgeniy'], $userIds['edirector'],false,'Пока');

        $noticeIds['firstNoticeToEvgeniy'] = $this->addNotice($userIds['evgeniy'], 'Первая заметка', false);
        $noticeIds['secondNoticeToEvgeniy'] = $this->addNotice($userIds['evgeniy'], 'Вторая заметка', true);
        $noticeIds['thirdNoticeToEvgeniy'] = $this->addNotice($userIds['evgeniy'], 'Третья заметка', false);

        $this->stdout("\nTest data was init\n");
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

    public function addComment($taskId, $senderId, $content, $commentId = null)
    {
        $commentId = $commentId ?? 'NULL';
        $this->db->createCommand("INSERT INTO comment (task_id, sender_id, content, comment_id,created_at, updated_at) VALUES 
                                      ({$taskId}, {$senderId}, '{$content}', {$commentId},{$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('comment_id_seq');
    }

    public function addLikeToTask($taskId, $userId, $liked)
    {
        $liked = $liked ? 'true' : 'false';
        $this->db->createCommand("INSERT INTO task_like (task_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$taskId}, {$userId}, {$liked}, {$this->getTime()}, {$this->getTime()})")->execute();

        return $this->db->getLastInsertID('task_like_id_seq');
    }

    public function addLikeToComment($commentId, $userId, $liked)
    {
        $liked = $liked ? 'true' : 'false';
        $this->db->createCommand("INSERT INTO comment_like (comment_id, user_id, liked, created_at, updated_at) VALUES 
                                      ({$commentId}, {$userId}, {$liked}, {$this->getTime()}, {$this->getTime()})")->execute();

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