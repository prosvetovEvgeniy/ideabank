<?php

namespace common\models\entities;

/**
 * Class TaskLikeEntity
 * @package common\models\entities
 *
 * @property int  $id
 * @property int  $taskId
 * @property int  $userId
 * @property bool $liked
 * @property int  $createdAt
 * @property int  $updatedAt
 */
class TaskLikeEntity
{
    protected $id;
    protected $taskId;
    protected $userId;
    protected $liked;
    protected $createdAt;
    protected $updatedAt;


    /**
     * TaskLikeEntity constructor.
     * @param int $taskId
     * @param int $userId
     * @param bool $liked
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     */
    public function __construct(int $taskId, int $userId, bool $liked, int $id = null,
                                int $createdAt = null, int $updatedAt = null)
    {
        $this->id = $id;
        $this->taskId = $taskId;
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
    public function getTaskId() { return $this->taskId; }

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


    // #################### SECTION OF LOGIC ######################

    public function like() { $this->liked = true; }

    public function dislike() { $this->liked = false; }
}




















