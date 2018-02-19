<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\comment\CommentViewRepository;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\task\TaskFileRepository;
use common\models\repositories\task\TaskLikeRepository;
use common\models\repositories\task\TaskRepository;
use common\models\repositories\user\UserRepository;
use Yii;

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
 * @property TaskFileEntity[] $files
 * @property TaskEntity[]     $children
 */
class TaskEntity implements IEntity
{
    /*
     * количество актуальных задач,
     * которые оторбражаются на странице /index
     */
    public const ACTUAL_TASKS_COUNT = 5;

    public const TITLE_MAX_LENGTH = 100;
    public const CONTENT_MAX_LENGTH = 10000;
    public const TITLE_MIN_LENGTH = 4;
    public const CONTENT_MIN_LENGTH = 10;

    public const STATUS_ON_CONSIDERATION = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_MERGED = 3;

    public const VISIBILITY_AREA_ALL = 0;
    public const VISIBILITY_AREA_REGISTERED = 1;
    public const VISIBILITY_AREA_PRIVATE = 2;

    public const LIST_STATUSES = [
        self::STATUS_ON_CONSIDERATION => 'на рассмотрении',
        self::STATUS_IN_PROGRESS      => 'в процессе',
        self::STATUS_COMPLETED        => 'завершена',
        self::STATUS_MERGED           => 'объединена с другой'
    ];

    public const LIST_STATUSES_PRIVATE_TASK = [
        self::STATUS_ON_CONSIDERATION => 'на рассмотрении',
        self::STATUS_IN_PROGRESS      => 'в процессе',
        self::STATUS_COMPLETED        => 'завершена',
    ];

    public const LIST_VISIBILITY_AREAS = [
        self::VISIBILITY_AREA_ALL        => 'доступна для всех',
        self::VISIBILITY_AREA_REGISTERED => 'доступна только для зарегистрированных',
        self::VISIBILITY_AREA_PRIVATE    => 'приватная'
    ];

    protected const DATE_ERROR_MESSAGE = 'дата не определена';
    protected const STATUS_ERROR_MESSAGE = 'статус не определен';

    protected const DATE_FORMAT = 'd.m.Y';

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
    protected $files;
    protected $children;


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
    public function setParentId (int $value = null) { $this->parentId = $value; }

    /**
     * @param int $value
     */
    public function setPlannedEndAt (int $value = null) { $this->plannedEndAt = $value; }

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
        if ($this->project === null) {
            $this->project = ProjectRepository::instance()->findOne(['id' => $this->getProjectId()]);
        }

        return $this->project;
    }

    /**
     * @return UserEntity
     */
    public function getAuthor()
    {
        if ($this->author === null) {
            $this->author = UserRepository::instance()->findOne(['id' => $this->getAuthorId()]);
        }

        return $this->author;
    }

    /**
     * @return TaskLikeEntity[]
     */
    public function getTaskLikes()
    {
        if ($this->taskLikes === null) {
            $this->taskLikes = TaskLikeRepository::instance()->findAll(['task_id' => $this->getId()]);
        }

        return $this->taskLikes;
    }

    /**
     * @return CommentEntity[]
     */
    public function getComments()
    {
        if ($this->comments === null) {
            $this->comments = CommentViewRepository::instance()->findAll(['task_id' => $this->getId()]);
        }

        return $this->comments;
    }

    /**
     * @return TaskEntity
     */
    public function getParent()
    {
        if ($this->parent === null && $this->parentId !== null) {
            $this->parent = TaskRepository::instance()->findOne(['id' => $this->getParentId()]);
        }

        return $this->parent;
    }

    public function getFiles()
    {
        if ($this->files === null) {
            $this->files = TaskFileRepository::instance()->findAll(['task_id' => $this->getId(), 'deleted' => false]);
        }

        return $this->files;
    }

    /**
     * @return TaskEntity[]|IEntity[]
     */
    public function getChildren()
    {
        if ($this->children === null){
            $this->children = TaskRepository::instance()->findAll([
                'parent_id' => $this->id,
                'deleted'   => false
            ]);
        }

        return $this->children;
    }


    // #################### SECTION OF LOGIC ######################

    /**
     * @return false|string
     */
    public function getCreatedDate()
    {
        return ($this->createdAt !== null) ?  date(self::DATE_FORMAT, $this->createdAt) : self::DATE_ERROR_MESSAGE;
    }

    /**
     * @return false|string
     */
    public function getUpdatedDate()
    {
        return ($this->updatedAt !== null) ?  date(self::DATE_FORMAT, $this->updatedAt) : self::DATE_ERROR_MESSAGE;
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
        return self::LIST_STATUSES[$this->status] ?? self::STATUS_ERROR_MESSAGE;
    }

    /**
     * @return int
     */
    public function getAmountLikes()
    {
        return TaskLikeRepository::instance()->getAmountLikes($this);
    }

    /**
     * @return int
     */
    public function getAmountDislikes()
    {
        return TaskLikeRepository::instance()->getAmountDislikes($this);
    }

    /**
     * @return null | UserEntity
     */
    public function getCurrentUser()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        return Yii::$app->user->identity->getUser();
    }

    /**
     * Проверяет лайкал ли задачу текущий пользователь
     *
     * @return bool
     */
    public function getCurrentUserLikedIt()
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return false;
        }

        $taskLike = TaskLikeRepository::instance()->findOne([
            'task_id' => $this->getId(),
            'user_id' => $user->getId(),
            'liked'   => true
        ]);

        return (!$taskLike) ? false : true;
    }

    /**
     * @return bool
     */
    public function getCurrentUserDislikedIt()
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return false;
        }

        $taskLike = TaskLikeRepository::instance()->findOne([
            'task_id' => $this->getId(),
            'user_id' => $user->getId(),
            'liked'   => false
        ]);

        return (!$taskLike) ? false : true;
    }

    /**
     * Метод возвращает файлы с mimeType - {image},
     * то есть картинки прикрепленные к данной
     * задаче
     *
     * @return TaskFileEntity[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getImagesToTask()
    {
        $imagesToTask = [];

        foreach ($this->getFiles() as $file) {
            if($file->isImage()) {
                $imagesToTask[] = $file;
            }
        }

        return $imagesToTask;
    }

    /**
     * Метод возвращает только файлы с mimeType
     * отличным от image, то есть не картинки
     * Не путать с методом getFiles(),
     * который возвращает файлы всех типов
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getFilesToTask()
    {
        $filesToTask = [];

        foreach ($this->getFiles() as $file) {
            if(!$file->isImage()) {
                $filesToTask[] = $file;
            }
        }

        return $filesToTask;
    }

    /**
     * @param int $status
     * @return bool
     */
    public function checkStatus(int $status)
    {
        return ($this->getStatus() === $status) ? true : false ;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (empty($this->getChildren())) ? false : true ;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return ($this->visibilityArea === self::VISIBILITY_AREA_PRIVATE) ? true : false ;
    }

    /**
     * @return bool
     */
    public function hasFiles()
    {
        return (empty($this->getFiles())) ? false : true ;
    }
}






















