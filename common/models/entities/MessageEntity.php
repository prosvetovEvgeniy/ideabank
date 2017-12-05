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

 * @property UserEntity $self
 * @property UserEntity $companion
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

    //кеш связанных сущностей
    protected $self;
    protected $companion;

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


    /**
     * @param string $value
     */
    public function setCompanionId(string $value) { $this->companionId = $value; }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return UserEntity
     */
    public function getSelf()
    {
        if($this->self === null)
        {
            $this->self = UserRepository::instance()->findOne(['id' => $this->getSelfId()]);
        }

        return $this->self;
    }

    /**
     * @return UserEntity
     */
    public function getCompanion()
    {
        if($this->companion === null)
        {
            $this->companion = UserRepository::instance()->findOne(['id' => $this->getCompanionId()]);
        }

        return $this->companion;
    }


    // #################### SECTION OF LOGIC ######################


}