<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use yii\base\Module;

class TestdataController extends Controller
{
    private $db;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->db = Yii::$app->db;
    }

    public function actionInit()
    {
        $this->db->createCommand("TRUNCATE company CASCADE")->execute();
        $this->db->createCommand("TRUNCATE project CASCADE")->execute();
        $this->db->createCommand("TRUNCATE users CASCADE")->execute();
        $this->db->createCommand("TRUNCATE participant CASCADE")->execute();
        $this->db->createCommand("TRUNCATE auth_assignment CASCADE")->execute();

        $auth = Yii::$app->authManager;

        $companyIds = [];
        $projectIds = [];
        $userIds = [];
        $participantIds = [];

        $companyIds['infSysId'] = $this->addCompany('Современные информационные системы');
        $companyIds['eCompanyId'] = $this->addCompany('E-company');

        $projectIds['vulcan'] = $this->addProject('Вулкан-М', $companyIds['infSysId']);
        $projectIds['github'] = $this->addProject('Github', $companyIds['eCompanyId']);
        $projectIds['vk'] = $this->addProject('Вконтакте', $companyIds['eCompanyId']);
        $projectIds['xabr'] = $this->addProject('Хабрахабр', $companyIds['eCompanyId']);

        $userIds['evgeniy'] = $this->addUser('evgeniy','123456','evgeniy@mail.ru');
        $userIds['admin'] = $this->addDirector('admin','123456','admin@mail.ru',
                                                '89131841102','Евгений', 'Просветов', 'Игоревич');
        $userIds['edirector'] = $this->addDirector('edirector','123456','edirector@mail.ru',
            '89131841102','edirector_first_name', 'edirector_second_name', 'edirector_last_name');

        $participantIds['evgeniyStub'] = $this->addParticipantStub($userIds['evgeniy']);
        $participantIds['evgeniyGithub'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['github']);
        $participantIds['evgeniyVk'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['vk']);
        $participantIds['evgeniyXabr'] = $this->addParticipant($userIds['evgeniy'], $companyIds['eCompanyId'], $projectIds['xabr']);

        $participantIds['adminDirector'] = $this->addParticipantDirector($userIds['admin'], $companyIds['infSysId']);
        $participantIds['adminVulcan'] = $this->addParticipant($userIds['admin'], $companyIds['infSysId'], $projectIds['vulcan']);

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

    private function addUser($username, $password, $email)
    {
        $password = Yii::$app->security->generatePasswordHash($password);

        $this->db->createCommand("INSERT INTO users (username, password, email, created_at, updated_at)
                                       VALUES ('{$username}', '{$password}', '{$email}', {$this->getTime()}, {$this->getTime()})")->execute();
        return $this->db->getLastInsertID('users_id_seq');
    }

    private function addDirector($username, $password, $email, $phone, $firstName, $secondName, $lastName)
    {
        $password = Yii::$app->security->generatePasswordHash($password);

        $this->db->createCommand("INSERT INTO users (username, password, email, phone, first_name, second_name, last_name, created_at, updated_at)
                                       VALUES ('{$username}', '{$password}', '{$email}', '{$phone}', '{$firstName}', '{$secondName}', '{$lastName}',
                                               {$this->getTime()}, {$this->getTime()})")->execute();
        return $this->db->getLastInsertID('users_id_seq');
    }

    public function addParticipantStub($userId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, created_at) VALUES ({$userId}, {$this->getTime()})")->execute();
        return $this->db->getLastInsertID('participant_id_seq');
    }

    public function addParticipant($userId, $companyId, $projectId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, project_id, approved, approved_at,created_at) 
                                      VALUES ({$userId},{$companyId}, {$projectId}, TRUE, {$this->getTime()},{$this->getTime()})")->execute();
        return $this->db->getLastInsertID('participant_id_seq');
    }

    public function addParticipantDirector($userId, $companyId)
    {
        $this->db->createCommand("INSERT INTO participant (user_id, company_id, approved, approved_at,created_at) 
                                      VALUES ({$userId},{$companyId}, TRUE, {$this->getTime()},{$this->getTime()})")->execute();
        return $this->db->getLastInsertID('participant_id_seq');
    }
    
    private function getTime()
    {
        return time();
    }
}