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
 * @property string $newRoleName
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
    protected $newRoleName;
    protected $createdAt;

    //кеш связанных сущностей
    private $changer;
    private $changeable;

    /**
     * AuthLogEntity constructor.
     * @param $changeableId
     * @param $newRoleName
     * @param null $changerId
     * @param null $id
     * @param null $createdAt
     * @param ParticipantEntity|null $changer
     * @param ParticipantEntity|null $changeable
     */
    public function __construct(
        $changeableId,
        $newRoleName,
        $changerId = null,
        $id = null,
        $createdAt = null,
        ParticipantEntity $changer = null,
        ParticipantEntity $changeable = null
    ) {
        $this->id = $id;
        $this->changerId = $changerId;
        $this->changeableId = $changeableId;
        $this->newRoleName = $newRoleName;
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
     * @return int|null
     */
    public function getChangerId() { return $this->changerId; }

    /**
     * @return int
     */
    public function getChangeableId() { return $this->changeableId; }

    /**
     * @return string
     */
    public function getNewRoleName() { return $this->newRoleName; }

    /**
     * @return int
     */
    public function getCreatedAt() { return $this->createdAt; }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param int $value
     */
    public function setChangerId(int $value = null) { $this->changerId = $value; }

    /**
     * @param int $value
     */
    public function setChangeableId(int $value) { $this->changeableId = $value; }

    /**
     * @param string $value
     */
    public function setNewRoleName(string $value) { $this->newRoleName = $value; }


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