<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\participant\ParticipantRepository;
use yii\helpers\Html;

/**
 * Class AuthAssignmentEntity
 * @package common\models\entities
 *
 * @property string $itemName
 * @property int    $userId
 * @property int    $createdAt
 *
 * @property ParticipantEntity $user
 */
class AuthAssignmentEntity implements IEntity
{
    //Название ролей из rbac
    public const ROLE_USER = 'user';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_PROJECT_DIRECTOR = 'projectDirector';
    public const ROLE_COMPANY_DIRECTOR = 'companyDirector';

    protected $itemName;
    protected $userId;
    protected $createdAt;

    //кеш связанных сущностей
    protected $participant;

    /**
     * AuthAssignmentEntity constructor.
     * @param string $itemName
     * @param int $userId
     * @param int $createdAt
     * @param ParticipantEntity|null $participant
     */
    public function __construct(
        string $itemName,
        int $userId,
        int $createdAt = null,
        ParticipantEntity $participant = null
    ) {
        $this->itemName = $itemName;
        $this->userId = $userId;
        $this->createdAt = $createdAt;

        $this->user = $participant;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @param bool $encode
     * @return string
     */
    public function getItemName(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->itemName) : $this->itemName;
    }

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
     * @return ParticipantEntity|null
     */
    public function getUser()
    {
        if($this->user === null) {
            $this->user = ParticipantRepository::instance()->findOne(['id' => $this->getUserId()]);
        }

        return $this->user;
    }


    // #################### SECTION OF LOGIC ######################

}