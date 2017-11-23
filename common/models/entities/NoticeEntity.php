<?php

namespace common\models\entities;
use common\models\repositories\UserRepository;

/**
 * Class NoticeEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $recipientId
 * @property string $content
 * @property int $createdAt
 * @property bool $viewed
 */
class NoticeEntity
{
    protected $id;
    protected $recipientId;
    protected $content;
    protected $createdAt;
    protected $viewed;

    /**
     * NoticeEntity constructor.
     * @param int $recipientId
     * @param string $content
     * @param int|null $id
     * @param int|null $createdAt
     * @param bool|null $viewed
     */
    public function __construct(int $recipientId, string $content, int $id = null,
                                int $createdAt = null, bool $viewed = null)
    {
        $this->id = $id;
        $this->recipientId = $recipientId;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->viewed = $viewed;
    }


    // #################### SECTION OF GETTERS ######################

    /**
     * @return int | null
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getRecipientId() { return $this->recipientId; }

    /**
     * @return string
     */
    public function getContent() { return $this->content; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return bool | null
     */
    public function getViewed() { return $this->viewed; }


    // #################### SECTION OF SETTERS ######################

    /**
     * @param int $value
     */
    public function setRecipientId (int $value) { $this->recipientId = $value; }

    /**
     * @param string $value
     */
    public function setContent (string $value) { $this->content = $value; }


    // #################### SECTION OF RELATIONS ######################

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return UserRepository::instance()->findOne(['id' => $this->getRecipientId()]);
    }


    // #################### SECTION OF LOGIC ######################
}

























