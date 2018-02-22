<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\user\UserRepository;
use yii\helpers\Html;

/**
 * Class MessageEntity
 * @package common\models\entities
 *
 * @property int    $id
 * @property int    $selfId
 * @property int    $companionId
 * @property string $content
 * @property bool   $isSender
 * @property bool   $viewed
 * @property int    $createdAt
 * @property bool   $deleted

 * @property UserEntity $self
 * @property UserEntity $companion
 */
class MessageEntity implements IEntity
{
    protected const DATE_ERROR_MESSAGE = '-';
    protected const DATE_FORMAT = 'd.m.y';

    protected $id;
    protected $selfId;
    protected $companionId;
    protected $content;
    protected $isSender;
    protected $viewed;
    protected $createdAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $self;
    protected $companion;

    /**
     * MessageEntity constructor.
     * @param int $selfId
     * @param int $companionId
     * @param string $content
     * @param bool $isSender
     * @param int|null $id
     * @param bool|null $viewed
     * @param int|null $createdAt
     * @param bool|null $deleted
     * @param UserEntity|null $self
     * @param UserEntity|null $companion
     */
    public function __construct(
        int $selfId,
        int $companionId,
        string $content,
        bool $isSender,
        int $id = null,
        bool $viewed = false,
        int $createdAt = null,
        bool $deleted = false,
        UserEntity $self = null,
        UserEntity $companion = null
    ) {
        $this->id = $id;
        $this->selfId = $selfId;
        $this->companionId = $companionId;
        $this->content = $content;
        $this->isSender = $isSender;
        $this->viewed = $viewed;
        $this->createdAt = $createdAt;
        $this->deleted = $deleted;

        $this->self = $self;
        $this->companion = $companion;
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
     * @param bool $encode
     * @return string
     */
    public function getContent(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->content) : $this->content;
    }

    /**
     * @return bool
     */
    public function getIsSender() { return $this->isSender; }

    /**
     * @return mixed
     */
    public function getViewed() { return $this->viewed; }

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

    /**
     * @param bool $value
     */
    public function setViewed(bool $value) { $this->viewed = $value; }

    // #################### SECTION OF RELATIONS ######################


    /**
     * @return UserEntity|null
     */
    public function getSelf()
    {
        if($this->self === null) {
            $this->self = UserRepository::instance()->findOne(['id' => $this->getSelfId()]);
        }

        return $this->self;
    }

    /**
     * @return UserEntity|null
     */
    public function getCompanion()
    {
        if($this->companion === null) {
            $this->companion = UserRepository::instance()->findOne(['id' => $this->getCompanionId()]);
        }

        return $this->companion;
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * @return false|string
     */
    public function getCreationDate()
    {
        return date(self::DATE_FORMAT, $this->createdAt) ?? self::DATE_ERROR_MESSAGE;
    }
}