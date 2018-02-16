<?php

namespace common\models\entities;


use common\models\interfaces\IEntity;
use common\models\repositories\rbac\AuthAssignmentRepository;
use common\models\repositories\company\CompanyRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\user\UserRepository;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;


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
 * @property array $roleList
 * @property \yii\rbac\ManagerInterface $auth
 */
class ParticipantEntity implements IEntity, IdentityInterface
{
    //названия ролей из RBAC
    public const ROLE_USER = 'user';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_PROJECT_DIRECTOR = 'projectDirector';
    public const ROLE_COMPANY_DIRECTOR = 'companyDirector';

    /**
     * эти константы используются в видах для
     * отображения статуса пользователя, если
     * он не имеет роли, но вступил в проект
     * или был заблокирован в нем
     */
    public const ROLE_BLOCKED = 'blocked';
    public const ROLE_ON_CONSIDERATION = 'on consideration';
    public const ROLE_UNDEFINED = 'role undefined';

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

    /**
     * список ролей и состояний на русском языке
     * @see getRoleNameOnRussian()
     */
    protected $roleList = [
        self::ROLE_USER             => 'Участник',
        self::ROLE_MANAGER          => 'Менеджер',
        self::ROLE_PROJECT_DIRECTOR => 'Директор проекта',
        self::ROLE_COMPANY_DIRECTOR => 'Директор компании',
        self::ROLE_BLOCKED          => 'Заблокирован',
        self::ROLE_ON_CONSIDERATION => 'На рассмотрении',
        self::ROLE_UNDEFINED        => 'Роль не определена'
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
     * @param int|null $deletedAt
     * @param bool $deleted
     * @param ProjectEntity|null $project
     * @param UserEntity|null $user
     * @param CompanyEntity|null $company
     */
    public function __construct(int $userId, int $companyId = null, int $projectId = null, bool $approved = false,
                                int $approvedAt = null, bool $blocked = false, int $blockedAt = null,
                                int $id = null, int $createdAt = null, int $updatedAt = null,
                                int $deletedAt = null, bool $deleted = false, ProjectEntity $project = null,
                                UserEntity $user = null, CompanyEntity $company = null)
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
        $this->deletedAt = $deletedAt;
        $this->deleted = $deleted;

        $this->auth = Yii::$app->authManager;

        $this->project = $project;
        $this->user = $user;
        $this->company = $company;
    }


    // ######## SECTION OF REALIZATION IDENTITY ############


    /**
     * @return int | null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password)
    {
        return Yii::$app->security->validatePassword($password, $this->getUser()->getPassword());
    }

    /**
     * @param int|string $id
     * @return ParticipantEntity|null
     */
    public static function findIdentity($id)
    {
        return ParticipantRepository::instance()->findOne(['id' => $id]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @return null|string
     */
    public function getAuthKey()
    {
        return $this->getUser()->getAuthKey();
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    // #################### SECTION OF GETTERS ######################


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

    /**
     * @return mixed
     */
    public function getDeletedAt() { return $this->deletedAt; }

    /**
     * @return bool
     */
    public function getDeleted() { return $this->deleted; }


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
    public function setApprovedAt (int $value = null) { $this->approvedAt = $value; }

    /**
     * @param int $value
     */
    public function setDeletedAt (int $value = null) { $this->deletedAt = $value; }

    /**
     * @param bool $value
     */
    public function setDeleted (bool $value) { $this->deleted = $value; }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        if($this->company === null)
        {
            $this->company = CompanyRepository::instance()->findOne(['id' => $this->getCompanyId()]);
        }

        return $this->company;
    }

    /**
     * @return ProjectEntity
     */
    public function getProject()
    {
        if($this->project === null)
        {
            $this->project = ProjectRepository::instance()->findOne(['id' => $this->getProjectId()]);
        }

        return $this->project;
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        if($this->user === null)
        {
            $this->user = UserRepository::instance()->findOne(['id' => $this->getUserId()]);
        }

        return $this->user;
    }

    /**
     * @return AuthAssignmentEntity
     */
    public function getAuthAssignment()
    {
        if($this->authAssignment === null)
        {
            $this->authAssignment = AuthAssignmentRepository::instance()->findOne(['user_id' => $this->getId()]);
        }

        return $this->authAssignment;
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * Возвращает значение роли пользователя (из RBAC)
     * или его статус (заблокирован, на рассмотрении),
     * если он не имеет роли
     *
     * @return int|null|string
     */
    public function getRoleName()
    {
        if($this->blocked) {
            return self::ROLE_BLOCKED;
        }
        else if(!$this->approved && !$this->blocked) {
            return self::ROLE_ON_CONSIDERATION;
        }
        else if($this->getAuthAssignment() !== null) {
            return $this->getAuthAssignment()->getItemName();
        }
        else {
            return self::ROLE_UNDEFINED;
        }
    }

    /**
     * @return string
     */
    public function getRoleNameOnRussian()
    {
        $roleName = $this->getRoleName();

        return $this->roleList[$roleName];
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
    public function isDirector()
    {
        $role = $this->getRoleName();

        if($role === self::ROLE_PROJECT_DIRECTOR || $role === self::ROLE_COMPANY_DIRECTOR)
        {
            return true;
        }

        return false;
    }
}