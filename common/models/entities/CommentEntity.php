<?php

namespace common\models\entities;


use common\models\repositories\CommentLikeRepository;
use common\models\repositories\CommentRepository;
use common\models\repositories\TaskRepository;
use common\models\repositories\UserRepository;

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
 */
class CommentEntity
{
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
     * CommentEntity constructor.
     * @param int $taskId
     * @param int $senderId
     * @param string $content
     * @param int|null $id
     * @param int|null $parentId
     * @param bool|null $private
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     */
    public function __construct(int $taskId, int $senderId, string $content,int $parentId = null,
                                bool $private = null, int $id = null, int $createdAt = null,
                                int $updatedAt = null, bool $deleted = null)
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


    // #################### SECTION OF RELATIONS ######################

    /**
     * @return CommentEntity
     * @throws \yii\db\Exception
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
     * @return TaskEntity
     * @throws \yii\db\Exception
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
     * @return UserEntity
     * @throws \yii\db\Exception
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
     * @return int
     */
    public function getAmountLikes()
    {
        return CommentLikeRepository::instance()->getAmountLikes($this);
    }

    /**
     * @return int
     */
    public function getAmountDislikes()
    {
        return CommentLikeRepository::instance()->getAmountDislikes($this);
    }
}















