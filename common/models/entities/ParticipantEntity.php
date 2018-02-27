<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\rbac\AuthAssignmentRepository;
use common\models\repositories\company\CompanyRepository;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\user\UserRepository;
use Yii;
use yii\rbac\ManagerInterface;


/**
 * Class ParticipantEntity
 * @package common\models\entities
 *
 * @property int  $id
 * @property int  $userId
 * @property int  $companyId
 * @property int  $projectId
 * @property bool $approved
 * @property int  $approvedAt
 * @property bool $blocked
 * @property int  $blockedAt
 * @property int  $createdAt
 * @property int  $updatedAt
 * @property int  deletedAt
 * @property bool $deleted
 *
 * @property CommentEntity        $company
 * @property ProjectEntity        $project
 * @property UserEntity           $user
 * @property AuthAssignmentEntity $authAssignment
 *
 * @property ManagerInterface $auth
 */
class ParticipantEntity implements IEntity
{
    protected const DATE_ERROR_MESSAGE = '-';

    private const DATE_FORMAT = 'd.m.Y';

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
    protected $deletedAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $company;
    protected $project;
    protected $user;
    protected $authAssignment;

    protected $auth;

    /**
     * ParticipantEntity constructor.
     * @param int $userId
     * @param int|null $companyId
     * @param int|null $projectId
     * @param bool $approved
     * @param int|null $approvedAt
     * @param bool $blocked
     * @param int|null $blockedAt
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param int|null $deletedAt
     * @param bool $deleted
     * @param ProjectEntity|null $project
     * @param UserEntity|null $user
     * @param CompanyEntity|null $company
     * @param AuthAssignmentEntity|null $authAssignment
     */
    public function __construct(
        int $userId,
        int $companyId = null,
        int $projectId = null,
        bool $approved = false,
        int $approvedAt = null,
        bool $blocked = false,
        int $blockedAt = null,
        int $id = null,
        int $createdAt = null,
        int $updatedAt = null,
        int $deletedAt = null,
        bool $deleted = false,
        ProjectEntity $project = null,
        UserEntity $user = null,
        CompanyEntity $company = null,
        AuthAssignmentEntity $authAssignment = null
    ) {
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
        $this->deletedAt = $deletedAt;
        $this->deleted = $deleted;

        $this->auth = Yii::$app->authManager;

        $this->project = $project;
        $this->user = $user;
        $this->company = $company;
        $this->authAssignment = $authAssignment;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int | null
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return int | null
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @return bool | null
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @return int | null
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * @return bool | null
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * @return int | null
     */
    public function getBlockedAt()
    {
        return $this->blockedAt;
    }

    /**
     * @return int | null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int | null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param int $value
     */
    public function setCompanyId(int $value)
    {
        $this->companyId = $value;
    }

    /**
     * @param int $value
     */
    public function setProjectId(int $value)
    {
        $this->projectId = $value;
    }

    /**
     * @param bool $value
     */
    public function setApproved(bool $value)
    {
        $this->approved = $value;
    }

    /**
     * @param int $value
     */
    public function setApprovedAt(int $value = null)
    {
        $this->approvedAt = $value;
    }

    /**
     * @param bool $value
     */
    public function setBlocked(bool $value)
    {
        $this->blocked = $value;
    }

    /**
     * @param int|null $value
     */
    public function setBlockedAt(int $value = null)
    {
        $this->blockedAt = $value;
    }

    /**
     * @param int $value
     */
    public function setDeletedAt(int $value = null)
    {
        $this->deletedAt = $value;
    }

    /**
     * @param bool $value
     */
    public function setDeleted(bool $value)
    {
        $this->deleted = $value;
    }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        if ($this->company === null) {
            $this->company = CompanyRepository::instance()->findOne(['id' => $this->getCompanyId()]);
        }

        return $this->company;
    }

    /**
     * @return ProjectEntity
     */
    public function getProject()
    {
        if ($this->project === null) {
            $this->project = ProjectRepository::instance()->findOne(['id' => $this->getProjectId()]);
        }

        return $this->project;
    }

    /**
     * @return UserEntity|null
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = UserRepository::instance()->findOne(['id' => $this->getUserId()]);
        }

        return $this->user;
    }

    /**
     * @return AuthAssignmentEntity
     */
    public function getAuthAssignment()
    {
        if ($this->authAssignment === null) {
            $this->authAssignment = AuthAssignmentRepository::instance()->findOne(['user_id' => $this->getId()]);
        }

        return $this->authAssignment;
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * Возвращает значение роли пользователя (из RBAC)
     * или его статус (заблокирован, на рассмотрении),
     *
     * @return int|null|string
     */
    public function getRoleName()
    {
        return $this->getAuthAssignment()->getItemName();
    }

    /**
     * @return false|string
     */
    public function getApprovedAtDate()
    {
        return ($this->approvedAt) ? date(self::DATE_FORMAT, $this->approvedAt) : self::DATE_ERROR_MESSAGE;
    }

    /**
     * @return bool
     */
    public function isCompanyDirector()
    {
        return $this->getRoleName() === AuthAssignmentEntity::ROLE_COMPANY_DIRECTOR;
    }

    /**
     * @return bool
     */
    public function isProjectDirector()
    {
        return $this->getRoleName() === AuthAssignmentEntity::ROLE_PROJECT_DIRECTOR;
    }

    /**
     * @return bool
     */
    public function isManager()
    {
        return $this->getRoleName() === AuthAssignmentEntity::ROLE_MANAGER;
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->getRoleName() === AuthAssignmentEntity::ROLE_USER;
    }

    /**
     * @return bool
     */
    public function onConsideration()
    {
        return $this->getRoleName() === AuthAssignmentEntity::ROLE_ON_CONSIDERATION;
    }

    /**
     * @return bool
     */
    public function blocked()
    {
        return $this->getRoleName() === AuthAssignmentEntity::ROLE_BLOCKED;
    }
}