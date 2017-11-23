<?php


namespace common\models\entities;
use common\models\repositories\UserRepository;

/**
 * Class MessageEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $selfId
 * @property int $companionId
 * @property string  $content
 * @property bool $isSender
 * @property int $createdAt
 * @property bool $deleted
 */
class MessageEntity
{
    protected $id;
    protected $selfId;
    protected $companionId;
    protected $content;
    protected $isSender;
    protected $createdAt;
    protected $deleted;

    /**
     * MessageEntity constructor.
     * @param int $selfId
     * @param int $companionId
     * @param bool $isSender
     * @param string $content
     * @param int|null $id
     * @param int|null $createdAt
     * @param bool|null $deleted
     */
    public function __construct(int $selfId, int $companionId, bool $isSender, string $content,
                                int $id = null, int $createdAt = null, bool $deleted = null)
    {
        $this->id = $id;
        $this->selfId = $selfId;
        $this->companionId = $companionId;
        $this->content = $content;
        $this->isSender = $isSender;
        $this->createdAt = $createdAt;
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
    public function getSelfId() { return $this->selfId; }

    /**
     * @return int
     */
    public function getCompanionId() { return $this->companionId; }

    /**
     * @return string
     */
    public function getContent() { return $this->content; }

    /**
     * @return bool
     */
    public function getIsSender() { return $this->isSender; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return bool | null
     */
    public function getDeleted() { return $this->deleted; }


    // #################### SECTION OF SETTERS ######################



    // #################### SECTION OF RELATIONS ######################

    /**
     * @return UserEntity
     */
    public function getSelf()
    {
        return UserRepository::instance()->findOne(['id' => $this->getSelfId()]);
    }

    /**
     * @return UserEntity
     */
    public function getCompanion()
    {
        return UserRepository::instance()->findOne(['id' => $this->getCompanionId()]);
    }


    // #################### SECTION OF LOGIC ######################

}