<?php

namespace common\models\entities;


use common\models\interfaces\IEntity;
use common\models\repositories\comment\CommentLikeRepository;
use common\models\repositories\comment\CommentRepository;
use common\models\repositories\task\TaskRepository;
use common\models\repositories\user\UserRepository;
use yii\helpers\Html;
use Yii;

/**
 * Class CommentEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $taskId
 * @property int $senderId
 * @property string  $content
 * @property int $parentId
 * @property bool $private
 * @property int $createdAt
 * @property int $updatedAt
 * @property bool $deleted

 * @property CommentEntity       $parent
 * @property TaskEntity          $task
 * @property UserEntity          $user
 * @property CommentLikeEntity[] $commentLikes
 *
 * @property int $likesAmount
 * @property int $dislikesAmount
 * @property bool $currentUserLikedIt
 * @property bool $currentUserDislikedIt
 */
class CommentEntity implements IEntity
{
    protected const DATE_ERROR_MESSAGE = 'дата не определена';
    protected const DATE_FORMAT = 'd-m-Y';

    protected $id;
    protected $taskId;
    protected $senderId;
    protected $content;
    protected $parentId;
    protected $private;
    protected $createdAt;
    protected $updatedAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $parent;
    protected $task;
    protected $user;
    protected $commentLikes;

    /**
     * поля вычисленные по
     * таблице CommentLike
     */
    protected $likesAmount;
    protected $dislikesAmount;
    protected $currentUserLikedIt;
    protected $currentUserDislikedIt;


    /**
     * CommentEntity constructor.
     * @param int $taskId
     * @param int $senderId
     * @param string $content
     * @param int|null $parentId
     * @param bool|null $private
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool $deleted
     * @param int $likesAmount
     * @param int $dislikesAmount
     * @param UserEntity|null $user
     * @param CommentEntity|null $parent
     * @param bool|null $currentUserLikedIt
     * @param bool|null $currentUserDislikedIt
     */
    public function __construct(int $taskId, int $senderId, string $content,int $parentId = null,
                                bool $private = false, int $id = null, int $createdAt = null,
                                int $updatedAt = null, bool $deleted = false, int $likesAmount = 0,
                                int $dislikesAmount = 0, UserEntity $user = null, CommentEntity $parent = null,
                                bool $currentUserLikedIt = null, bool $currentUserDislikedIt = null)
    {
        $this->id = $id;
        $this->taskId = $taskId;
        $this->senderId = $senderId;
        $this->content = $content;
        $this->parentId = $parentId;
        $this->private = $private;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deleted = $deleted;

        $this->likesAmount = $likesAmount;
        $this->dislikesAmount = $dislikesAmount;

        $this->user = $user;
        $this->parent = $parent;

        $this->currentUserLikedIt = $currentUserLikedIt;
        $this->currentUserDislikedIt = $currentUserDislikedIt;
    }


    // #################### SECTION OF GETTERS ######################

    /**
     * @return int | null
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getTaskId() { return $this->taskId; }

    /**
     * @return int
     */
    public function getSenderId() { return $this->senderId; }

    /**
     * @return string
     */
    public function getContent() { return $this->content; }

    /**
     * @return int | null
     */
    public function getParentId() { return $this->parentId; }

    /**
     * @return bool | null
     */
    public function getPrivate() { return $this->private; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int | null
     */
    public function getUpdatedAt() { return $this->updatedAt; }

    /**
     * @return bool | null
     */
    public function getDeleted() { return $this->deleted; }

    /**
     * @return int
     */
    public function getLikesAmount() { return $this->likesAmount; }

    /**
     * @return int
     */
    public function getDislikesAmount() { return $this->dislikesAmount; }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param string $value
     */
    public function setContent (string $value) { $this->content = $value; }

    /**
     * @param int $value
     */
    public function setParentId (int $value) { $this->parentId = $value; }

    /**
     * @param bool $value
     */
    public function setPrivate (bool $value) { $this->private = $value; }

    /**
     * @param bool $value
     */
    public function setDeleted (bool $value) { $this->deleted = $value; }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return CommentEntity|null
     */
    public function getParent()
    {
        if($this->parent === null)
        {
            $this->parent = CommentRepository::instance()->findOne(['id' => $this->getParentId()]);
        }

        return $this->parent;
    }

    /**
     * @return TaskEntity|null
     */
    public function getTask()
    {
        if($this->task === null)
        {
            $this->task = TaskRepository::instance()->findOne(['id' => $this->getTaskId()]);
        }

        return $this->task;
    }

    /**
     * @return UserEntity|null
     */
    public function getUser()
    {
        if($this->user === null)
        {
            $this->user = UserRepository::instance()->findOne(['id' => $this->getSenderId()]);
        }

        return $this->user;
    }

    /**
     * @return CommentLikeEntity[]
     */
    public function getCommentLikes()
    {
        if($this->commentLikes === null)
        {
            $this->commentLikes = CommentLikeRepository::instance()->findAll(['comment_id' => $this->getId()]);
        }

        return $this->commentLikes;
    }


    // #################### SECTION OF LOGIC ######################

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate()
    {
        return  Yii::$app->formatter->asDate($this->getCreatedAt(), 'short');
    }

    /**
     * @return bool | null
     */
    public function getCurrentUserLikedIt() { return $this->currentUserLikedIt; }

    /**
     * @return bool | null
     */
    public function getCurrentUserDislikedIt() { return $this->currentUserDislikedIt; }

    /**
     * @return bool
     */
    public function isOwn()
    {
        if(Yii::$app->user->isGuest)
        {
            return false;
        }

        /**
         * @var UserEntity $user
         */
        $user = Yii::$app->user->identity->getUser();

        return ($this->getSenderId() === $user->getId()) ? true : false;
    }
}















