<?php

namespace common\models\entities;
use common\models\interfaces\IEntity;
use common\models\repositories\UserRepository;

/**
 * Class AuthAssignmentEntity
 * @package common\models\entities
 *
 * @property string $itemName
 * @property int    $userId
 * @property int    $createdAt
 *
 * @property UserEntity $user
 */
class AuthAssignmentEntity implements IEntity
{
    protected $itemName;
    protected $userId;
    protected $createdAt;

    //кеш связанных сущностей
    protected $user;


    /**
     * AuthAssignmentEntity constructor.
     * @param string $itemName
     * @param int $userId
     * @param int $createdAt
     */
    public function __construct(string $itemName, int $userId, int $createdAt)
    {
        $this->itemName = $itemName;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return string
     */
    public function getItemName() { return $this->itemName; }

    /**
     * @return int
     */
    public function getUserId() { return $this->userId; }

    /**
     * @return int
     */
    public function getCreatedAt() { return $this->createdAt; }


    // #################### SECTION OF SETTERS ######################

    /**
     * @param string $value
     */
    public function setItemName(string $value) { $this->itemName = $value; }

    /**
     * @param int $value
     */
    public function setUserId(int $value) { $this->userId = $value; }


    // #################### SECTION OF RELATIONS ######################

    /**
     * @return UserEntity|null
     */
    public function getUser()
    {
        if($this->user === null)
        {
            $this->user = UserRepository::instance()->findOne(['id' => $this->getUserId()]);
        }

        return $this->user;
    }


    // #################### SECTION OF LOGIC ######################

}