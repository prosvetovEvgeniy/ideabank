<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\company\CompanyRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\task\TaskRepository;
use yii\helpers\Html;

/**
 * Class ProjectEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property string $name
 * @property int $companyId
 * @property string $description
 * @property int $defaultVisibilityArea
 * @property int $createdAt
 * @property int $updatedAt
 * @property bool $deleted
 *
 * @property CompanyEntity       $company
 * @property TaskEntity[]        $tasks
 * @property ParticipantEntity[] $participants
 *
 * @property TaskRepository $taskRepository
 */
class ProjectEntity implements IEntity
{
    public const VISIBILITY_AREA_ALL = 0;
    public const VISIBILITY_AREA_REGISTERED = 1;
    public const VISIBILITY_AREA_PRIVATE = 2;

    private const DATE_FORMAT = 'Y-m-d';

    protected $id;
    protected $name;
    protected $companyId;
    protected $description;
    protected $defaultVisibilityArea;
    protected $createdAt;
    protected $updatedAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $company;
    protected $tasks;
    protected $participants;

    protected $taskRepository;

    /**
     * ProjectEntity constructor.
     * @param string $name
     * @param int $companyId
     * @param string $description
     * @param int|null $defaultVisibilityArea
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     */
    public function __construct(
        string $name,
        int $companyId,
        string $description = null,
        int $defaultVisibilityArea = null,
        int $id = null,
        int $createdAt = null,
        int $updatedAt = null,
        bool $deleted = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->companyId = $companyId;
        $this->description = $description;
        $this->defaultVisibilityArea = $defaultVisibilityArea;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deleted = $deleted;

        $this->taskRepository = new TaskRepository();
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return int | null
     */
    public function getId() { return $this->id; }

    /**
     * @param bool $encode
     * @return string
     */
    public function getName(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->name) : $this->name;
    }

    /**
     * @return int
     */
    public function getCompanyId() { return $this->companyId; }

    /**
     * @param bool $encode
     * @return string
     */
    public function getDescription(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->description) : $this->description;
    }

    /**
     * @return int | null
     */
    public function getDefaultVisibilityArea() { return $this->defaultVisibilityArea; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int | null
     */
    public function getUpdatedAt() { return $this->updatedAt; }

    /**
     * @return boolean | null
     */
    public function getDeleted() { return $this->deleted; }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param string $value
     */
    public function setName (string $value) { $this->name = $value; }

    /**
     * @param integer $value
     */
    public function setCompanyId (int $value) { $this->companyId = $value; }

    /**
     * @param string $value
     */
    public function setDescription(string $value) { $this->description = $value; }

    /**
     * @param integer $value
     */
    public function setDefaultVisibilityArea (int $value) { $this->defaultVisibilityArea = $value; }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        if ($this->company === null) {
            $this->company = CompanyRepository::instance()->findOne(['id' => $this->getCompanyId(), 'deleted' => false]);
        }

        return $this->company;
    }

    /**
     * @return TaskEntity[]
     */
    public function getTasks()
    {
        if ($this->tasks === null) {
            $this->tasks = TaskRepository::instance()->findAll(['project_id' => $this->getId(), 'deleted' => false]);
        }

        return $this->tasks;
    }

    /**
     * @return ParticipantEntity[]
     */
    public function getParticipants()
    {
        if($this->participants === null)
        {
            $this->participants = ParticipantRepository::instance()->findAll([
                'project_id' => $this->getId(),
                'deleted'    => false
            ]);
        }

        return $this->participants;
    }


    // #################### SECTION OF LOGIC ######################

    
    /**
     * @return int
     */
    public function getAmountTasks()
    {
        return $this->taskRepository->getAmountTasks($this);
    }

    /**
     * @return int
     */
    public function getAmountCompletedTasks()
    {
        return $this->taskRepository->getAmountCompletedTasks($this);
    }

    /**
     * @return int
     */
    public function getAmountNotCompletedTasks()
    {
        return $this->taskRepository->getAmountNotCompletedTasks($this);
    }

    /**
     * @param UserEntity $user
     * @return int
     */
    public function getAmountTasksByAuthor(UserEntity $user)
    {
        return $this->taskRepository->getAmountTasksByAuthorForProject($this, $user);
    }

    /**
     * @return false|string
     */
    public function getCreatedAtDate()
    {
        return date(self::DATE_FORMAT, $this->getCreatedAt());
    }
}