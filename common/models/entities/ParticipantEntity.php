<?php

namespace common\models\entities;


use common\models\repositories\CompanyRepository;
use common\models\repositories\ProjectRepository;
use common\models\repositories\UserRepository;
use Yii;


/**
 * Class ParticipantEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $userId
 * @property int $companyId
 * @property int $projectId
 * @property bool $approved
 * @property int $approvedAt
 * @property bool $blocked
 * @property int $blockedAt
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property array $listRolesAsText
 * @property \yii\rbac\ManagerInterface $auth
 */
class ParticipantEntity
{
    public const ROLE_USER = 'user';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_PROJECT_DIRECTOR = 'projectDirector';
    public const ROLE_COMPANY_DIRECTOR = 'companyDirector';

    protected $id;
    protected $userId;
    protected $companyId;
    protected $projectId;
    protected $approved;
    protected $approvedAt;
    protected $blocked;
    protected $blockedAt;
    protected $createdAt;
    protected $updatedAt;

    protected $listRolesAsText = [
        self::ROLE_USER             => 'Участник',
        self::ROLE_MANAGER          => 'Менеджер',
        self::ROLE_PROJECT_DIRECTOR => 'Директор проекта',
        self::ROLE_COMPANY_DIRECTOR => 'Директор компании',
    ];

    protected $auth;

    /**
     * ParticipantEntity constructor.
     * @param int $userId
     * @param int|null $companyId
     * @param int|null $projectId
     * @param bool|null $approved
     * @param int|null $approvedAt
     * @param bool|null $blocked
     * @param int|null $blockedAt
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     */
    public function __construct(int $userId, int $companyId = null, int $projectId = null, bool $approved = null,
                                int $approvedAt = null, bool $blocked = null, int $blockedAt = null,
                                int $id = null, int $createdAt = null, int $updatedAt = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->companyId = $companyId;
        $this->projectId = $projectId;
        $this->approved = $approved;
        $this->approvedAt = $approvedAt;
        $this->blocked = $blocked;
        $this->blockedAt = $blockedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->auth = Yii::$app->authManager;
    }


    // #################### SECTION OF GETTERS ######################

    /**
     * @return int | null
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getUserId() { return $this->userId; }

    /**
     * @return int | null
     */
    public function getCompanyId() { return $this->companyId; }

    /**
     * @return int | null
     */
    public function getProjectId() { return $this->projectId; }

    /**
     * @return bool | null
     */
    public function getApproved() { return $this->approved; }

    /**
     * @return int | null
     */
    public function getApprovedAt() { return $this->approvedAt; }

    /**
     * @return bool | null
     */
    public function getBlocked() { return $this->blocked; }

    /**
     * @return int | null
     */
    public function getBlockedAt() { return $this->blockedAt; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int | null
     */
    public function getUpdatedAt() { return $this->updatedAt; }


    // #################### SECTION OF SETTERS ######################

    /**
     * @param int $value
     */
    public function setCompanyId (int $value) { $this->companyId = $value; }

    /**
     * @param int $value
     */
    public function setProjectId (int $value) { $this->projectId = $value; }

    /**
     * @param bool $value
     */
    public function setApproved (bool $value) { $this->approved = $value; }

    /**
     * @param int $value
     */
    public function setApprovedAt (int $value) { $this->approvedAt = $value; }


    // #################### SECTION OF RELATIONS ######################

    public function getCompany()
    {
        return CompanyRepository::instance()->findOne(['id' => $this->getCompanyId()]);
    }

    public function getProject()
    {
        return ProjectRepository::instance()->findOne(['id' => $this->getProjectId()]);
    }

    public function getUser()
    {
        return UserRepository::instance()->findOne(['id' => $this->getUserId()]);
    }


    // #################### SECTION OF LOGIC ######################

    /**
     * Переводит название роли на русский язык
     *
     * @return mixed|string
     */
    public function getRoleName()
    {
        $role = $this->auth->getRolesByUser($this->getId());

        $key = key($role);

        return $this->listRolesAsText[$key] ?? 'Роль не определена';
    }
}





























