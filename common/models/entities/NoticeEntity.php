<?php

namespace common\models\entities;


use common\models\interfaces\IEntity;
use common\models\repositories\UserRepository;

/**
 * Class NoticeEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $recipientId
 * @property int $senderId
 * @property string $content
 * @property string $link
 * @property int $createdAt
 *
 * @property UserEntity $sender;
 * @property UserEntity $recipient
 */
class NoticeEntity implements IEntity
{
    private const DATE_FORMAT = 'Y-m-d';

    protected $id;
    protected $recipientId;
    protected $senderId;
    protected $content;
    protected $link;
    protected $createdAt;

    //кеш связанных сущностей
    protected $sender;
    protected $recipient;

    /**
     * NoticeEntity constructor.
     * @param int $recipientId
     * @param string $content
     * @param string $link
     * @param int|null $senderId
     * @param int|null $id
     * @param int|null $createdAt
     * @param bool|null $viewed
     * @param UserEntity|null $sender
     * @param UserEntity|null $recipient
     */
    public function __construct(int $recipientId, string $content,string $link, int $senderId = null,
                                int $id = null, int $createdAt = null, bool $viewed = null,
                                UserEntity $sender = null, UserEntity $recipient = null)
    {
        $this->id = $id;
        $this->recipientId = $recipientId;
        $this->senderId = $senderId;
        $this->content = $content;
        $this->link = $link;
        $this->createdAt = $createdAt;
        $this->viewed = $viewed;

        $this->sender = $sender;
        $this->recipient = $recipient;
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
     * @return int
     */
    public function getSenderId() { return $this->senderId; }

    /**
     * @return string
     */
    public function getContent() { return $this->content; }

    /**
     * @return string
     */
    public function getLink() { return $this->link; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }


    // #################### SECTION OF SETTERS ######################

    /**
     * @param int $value
     */
    public function setRecipientId (int $value) { $this->recipientId = $value; }

    /**
     * @param int $value
     */
    public function setSenderId (int $value) { $this->senderId = $value; }

    /**
     * @param string $value
     */
    public function setContent (string $value) { $this->content = $value; }

    /**
     * @param string $value
     */
    public function setLink (string $value) { $this->link = $value; }


    // #################### SECTION OF RELATIONS ######################

    /**
     * @return UserEntity|null
     */
    public function getSender()
    {
        if($this->sender === null)
        {
            $this->sender = UserRepository::instance()->findOne(['id' => $this->getSenderId()]);
        }

        return $this->sender;
    }

    /**
     * @return UserEntity|null
     */
    public function getRecipient()
    {
        if($this->recipient === null)
        {
            $this->recipient = UserRepository::instance()->findOne(['id' => $this->getRecipientId()]);
        }

        return $this->recipient;
    }


    // #################### SECTION OF LOGIC ######################


    public function getCreatedAtDate()
    {
        return date(self::DATE_FORMAT, $this->getCreatedAt());
    }
}

























