<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\participant\ParticipantRepository;

/**
 * Class AuthLogEntity
 * @package common\models\entities
 *
 * @property int    $id
 * @property int    $changerId
 * @property int    $changeableId
 * @property string $roleName
 * @property int    $createdAt
 *
 * @property ParticipantEntity $changer
 * @property ParticipantEntity $changeable
 */
class AuthLogEntity implements IEntity
{
    protected $id;
    protected $changerId;
    protected $changeableId;
    protected $roleName;
    protected $createdAt;

    //кеш связанных сущностей
    private $changer;
    private $changeable;

    /**
     * AuthLogEntity constructor.
     * @param $changerId
     * @param $changeableId
     * @param $roleName
     * @param null $id
     * @param null $createdAt
     * @param ParticipantEntity|null $changer
     * @param ParticipantEntity|null $changeable
     */
    public function __construct(
        $changerId,
        $changeableId,
        $roleName,
        $id = null,
        $createdAt = null,
        ParticipantEntity $changer = null,
        ParticipantEntity $changeable = null
    ) {
        $this->id = $id;
        $this->changerId = $changerId;
        $this->changeableId = $changeableId;
        $this->roleName = $roleName;
        $this->createdAt = $createdAt;

        $this->changer = $changer;
        $this->changeable = $changeable;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return int|null
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getChangerId() { return $this->changerId; }

    /**
     * @return int
     */
    public function getChangeableId() { return $this->changeableId; }

    /**
     * @return string
     */
    public function getRoleName() { return $this->roleName; }

    /**
     * @return int
     */
    public function getCreatedAt() { return $this->createdAt; }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param int $value
     */
    public function setChangerId(int $value) { $this->changerId = $value; }

    /**
     * @param int $value
     */
    public function setChangeableId(int $value) { $this->changeableId = $value; }

    /**
     * @param string $value
     */
    public function setRoleName(string $value) { $this->roleName = $value; }


    // #################### SECTION OF RELATIONS ######################


    /**
     * @return ParticipantEntity|null
     */
    public function getChanger()
    {
        if ($this->changer === null) {
            return ParticipantRepository::instance()->findOne(['id' => $this->changerId]);
        }

        return $this->changer;
    }

    /**
     * @return ParticipantEntity|null
     */
    public function getChangeable()
    {
        if ($this->changeable === null) {
            return ParticipantRepository::instance()->findOne(['id' => $this->changeableId]);
        }

        return $this->changeable;
    }


    // #################### SECTION OF LOGIC ######################


}