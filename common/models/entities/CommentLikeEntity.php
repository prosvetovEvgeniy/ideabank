<?php

namespace common\models\entities;


use common\models\repositories\CommentRepository;
use common\models\repositories\UserRepository;

/**
 * Class CommentLikeEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $commentId
 * @property int $userId
 * @property bool $liked
 * @property int $createdAt
 * @property int $updatedAt

 * @property UserEntity    $user
 * @property CommentEntity $comment
 */
class CommentLikeEntity
{
    protected $id;
    protected $commentId;
    protected $userId;
    protected $liked;
    protected $createdAt;
    protected $updatedAt;

    //кеш связанных сущностей
    protected $user;
    protected $comment;

    /**
     * CommentLikeEntity constructor.
     * @param int $commentId
     * @param int $userId
     * @param bool $liked
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     */
    public function __construct(int $commentId, int $userId, bool $liked, int $id = null,
                                int $createdAt = null, int $updatedAt = null)
    {
        $this->id = $id;
        $this->commentId = $commentId;
        $this->userId = $userId;
        $this->liked = $liked;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return int | null
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getCommentId() { return $this->commentId; }

    /**
     * @return int
     */
    public function getUserId() { return $this->userId; }

    /**
     * @return bool | null
     */
    public function getLiked() { return $this->liked; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int
     */
    public function getUpdatedAt() { return $this->updatedAt; }


    // #################### SECTION OF SETTERS ######################


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return UserEntity
     */
    public function getUser()
    {
        if ($this->user === null)
        {
            $this->user = UserRepository::instance()->findOne(['id' => $this->getUserId()]);
        }

        return $this->user;
    }

    /**
     * @return CommentEntity
     */
    public function getComment()
    {
        if($this->comment === null)
        {
            $this->comment = CommentRepository::instance()->findOne(['id' => $this->getCommentId()]);
        }

        return $this->comment;
    }


    // #################### SECTION OF LOGIC ######################


    public function like() { $this->liked = true; }

    public function dislike() { $this->liked = false; }
}