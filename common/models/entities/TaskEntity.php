<?php

namespace common\models\entities;


use common\models\repositories\CommentRepository;
use common\models\repositories\ProjectRepository;
use common\models\repositories\TaskLikeRepository;
use common\models\repositories\TaskRepository;
use common\models\repositories\UserRepository;

/**
 * Class TaskEntity
 * @package common\models\entities
 *
 * @property array $listStatusesAsText
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $authorId
 * @property int $projectId
 * @property int $status
 * @property int $visibilityArea
 * @property int $parentId
 * @property int $plannedEndAt
 * @property int $endAt
 * @property int $createdAt
 * @property int $updatedAt
 * @property bool $deleted
 *
 * @property ProjectEntity    $project
 * @property UserEntity       $author
 * @property TaskLikeEntity[] $taskLikes
 * @property CommentEntity[]  $comments
 * @propery  TaskEntity       $parent
 */
class TaskEntity
{
    public const STATUS_ON_CONSIDERATION = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_MERGED = 3;

    public const LIST_STATUSES_AS_TEXT = [
        self::STATUS_ON_CONSIDERATION => 'на рассмотрении',
        self::STATUS_IN_PROGRESS      => 'в процессе',
        self::STATUS_COMPLETED        => 'завершена',
        self::STATUS_MERGED           => 'объединена с другой'
    ];

    protected const DATE_ERROR_MESSAGE = 'дата не определена';
    protected const STATUS_ERROR_MESSAGE = 'статус не определен';

    protected const DATE_FORMAT = 'Y-m-d';

    protected $id;
    protected $title;
    protected $content;
    protected $authorId;
    protected $projectId;
    protected $status;
    protected $visibilityArea;
    protected $parentId;
    protected $plannedEndAt;
    protected $endAt;
    protected $createdAt;
    protected $updatedAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $project;
    protected $author;
    protected $taskLikes;
    protected $comments;
    protected $parent;

    /**
     * TaskEntity constructor.
     * @param string $title
     * @param string $content
     * @param int $authorId
     * @param int $projectId
     * @param int|null $id
     * @param int|null $status
     * @param int|null $visibilityArea
     * @param int|null $parentId
     * @param int|null $plannedEndAt
     * @param int|null $endAt
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     */
    public function __construct(string $title, string $content, int $authorId, int $projectId,
                                int $status = null, int $visibilityArea = null, int $parentId = null,
                                int $plannedEndAt = null, int $endAt = null, int $id = null,
                                 int $createdAt = null, int $updatedAt = null, bool $deleted = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->projectId = $projectId;
        $this->status = $status;
        $this->visibilityArea = $visibilityArea;
        $this->parentId = $parentId;
        $this->plannedEndAt = $plannedEndAt;
        $this->endAt = $endAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deleted = $deleted;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return int|null
     */
    public function getId() { return $this->id; }

    /**
     * @return string
     */
    public function getTitle() { return $this->title; }

    /**
     * @return string
     */
    public function getContent() { return $this->content; }

    /**
     * @return int
     */
    public function getAuthorId() { return $this->authorId; }

    /**
     * @return int
     */
    public function getProjectId() { return $this->projectId; }

    /**
     * @return int|null
     */
    public function getStatus() { return $this->status; }

    /**
     * @return int
     */
    public function getVisibilityArea() { return $this->visibilityArea; }

    /**
     * @return int|null
     */
    public function getParentId() { return $this->parentId; }

    /**
     * @return int|null
     */
    public function getPlannedEndAt() { return $this->plannedEndAt; }

    /**
     * @return int|null
     */
    public function getEndAt() { return $this->endAt; }

    /**
     * @return int
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int
     */
    public function getUpdatedAt() { return $this->updatedAt; }

    /**
     * @return bool
     */
    public function getDeleted() { return $this->deleted; }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param string $value
     */
    public function setTitle (string $value) { $this->title = $value; }

    /**
     * @param string $value
     */
    public function setContent (string $value) { $this->content = $value; }

    /**
     * @param int $value
     */
    public function setAuthorId (int $value) { $this->authorId = $value; }

    /**
     * @param int $value
     */
    public function setProjectId (int $value) { $this->projectId = $value; }

    /**
     * @param int $value
     */
    public function setStatus (int $value) { $this->status = $value; }

    /**
     * @param int $value
     */
    public function setVisibilityArea (int $value) { $this->visibilityArea = $value; }

    /**
     * @param int $value
     */
    public function setParentId (int $value) { $this->parentId = $value; }

    /**
     * @param int $value
     */
    public function setPlannedEndAt (int $value) { $this->plannedEndAt = $value; }

    /**
     * @param int $value
     */
    public function setEndAt (int $value) { $this->endAt = $value; }


    // #################### SECTION OF RELATIONS ######################


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
    public function getAuthor()
    {
        if($this->author === null)
        {
            $this->author = UserRepository::instance()->findOne(['id' => $this->getAuthorId()]);
        }

        return $this->author;
    }

    /**
     * @return TaskLikeEntity[]
     */
    public function getTaskLikes()
    {
        if($this->taskLikes === null)
        {
            $this->taskLikes = TaskLikeRepository::instance()->findAll(['task_id' => $this->getId()]);
        }

        return $this->taskLikes;
    }

    /**
     * @return CommentEntity[]
     */
    public function getComments()
    {
        if($this->comments === null)
        {
            $this->comments = CommentRepository::instance()->findAll(['task_id' => $this->getId()]);
        }

        return $this->comments;
    }

    /**
     * @return TaskEntity
     */
    public function getParent()
    {
        if($this->parent === null && $this->parentId !== null)
        {
            $this->parent = TaskRepository::instance()->findOne(['id' => $this->getParentId()]);
        }

        return $this->parent;
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * @return false|string
     */
    public function getCreatedDate()
    {
        return ($this->createdAt !== null) ? date(self::DATE_FORMAT, $this->createdAt) : self::DATE_ERROR_MESSAGE;
    }

    /**
     * @return false|string
     */
    public function getUpdatedDate()
    {
        return ($this->updatedAt !== null) ? date(self::DATE_FORMAT, $this->updatedAt) : self::DATE_ERROR_MESSAGE;
    }

    /**
     * @return false|string
     */
    public function getPlannedEndDate()
    {
        return ($this->plannedEndAt !== null) ? date(self::DATE_FORMAT, $this->plannedEndAt) : self::DATE_ERROR_MESSAGE;
    }

    /**
     * @return false|string
     */
    public function getEndDate()
    {
        return ($this->endAt !== null) ? date(self::DATE_FORMAT, $this->endAt) : self::DATE_ERROR_MESSAGE;
    }

    /**
     * @return string
     */
    public function getStatusAsText()
    {
        return self::LIST_STATUSES_AS_TEXT[$this->status] ?? self::STATUS_ERROR_MESSAGE;
    }
}