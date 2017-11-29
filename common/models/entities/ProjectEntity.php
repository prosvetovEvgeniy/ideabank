<?php

namespace common\models\entities;


use common\models\repositories\CompanyRepository;
use common\models\repositories\TaskRepository;

/**
 * Class ProjectEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property string $name
 * @property int $companyId
 * @property int $defaultVisibilityArea
 * @property int $createdAt
 * @property int $updatedAt
 * @property bool $deleted
 *
 * @property TaskRepository $taskRepository
 */
class ProjectEntity
{
    protected $id;
    protected $name;
    protected $companyId;
    protected $defaultVisibilityArea;
    protected $createdAt;
    protected $updatedAt;
    protected $deleted;

    protected $taskRepository;


    /**
     * ProjectEntity constructor.
     * @param string $name
     * @param int $companyId
     * @param int|null $id
     * @param int|null $defaultVisibilityArea
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     */
    public function __construct(string $name, int $companyId, int $defaultVisibilityArea = null, int $id = null,
                                int $createdAt = null, int $updatedAt = null, bool $deleted = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->companyId = $companyId;
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
     * @return string
     */
    public function getName() { return $this->name; }

    /**
     * @return int
     */
    public function getCompanyId() { return $this->companyId; }

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
     * @param integer $value
     */
    public function setDefaultVisibilityArea (int $value) { $this->defaultVisibilityArea = $value; }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        return CompanyRepository::instance()->findOne(['id' => $this->getCompanyId(), 'deleted' => false]);
    }

    /**
     * @return TaskEntity[]
     */
    public function getTasks()
    {
        return TaskRepository::instance()->findAll(['project_id' => $this->getId(), 'deleted' => false]);
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function getCompletedTasks(int $limit = null, int $offset = null)
    {
        return $this->taskRepository->findCompletedTasks($this, $limit, $offset);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function getNotCompletedTasks(int $limit = null, int $offset = null)
    {
        return $this->taskRepository->findNotCompletedTasks($this, $limit, $offset);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function getMergedTasks(int $limit = null, int $offset = null)
    {
        return $this->taskRepository->findMergedTasks($this, $limit, $offset);
    }

    /**
     * @param UserEntity $user
     * @param int|null $limit
     * @param int|null $offset
     * @return TaskEntity[]
     */
    public function getTasksByAuthor(UserEntity $user, int $limit = null, int $offset = null)
    {
        return $this->taskRepository->findTasksByAuthor($this, $user, $limit, $offset);
    }

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
        return $this->taskRepository->getAmountTasksByAuthor($this, $user);
    }
}