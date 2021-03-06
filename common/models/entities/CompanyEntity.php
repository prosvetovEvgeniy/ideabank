<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectRepository;
use yii\helpers\Html;

/**
 * Class CompanyEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property string $name
 * @property int $createdAt
 * @property int $updatedAt
 * @property bool $deleted

 * @property ProjectEntity       $projects
 * @property ParticipantEntity[] $participants
 */
class CompanyEntity implements IEntity
{
    protected $id;
    protected $name;
    protected $createdAt;
    protected $updatedAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $projects;
    protected $participants;

    /**
     * CompanyEntity constructor.
     * @param string $name
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     * @param array|null $projects
     * @param array|null $participants
     */
    public function __construct(
        string $name,
        int $id = null,
        int $createdAt = null,
        int $updatedAt = null,
        bool $deleted = null,
        array $projects = null,
        array $participants = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deleted = $deleted;

        $this->projects = $projects;
        $this->participants = $participants;
    }


    // #################### SECTION OF GETTERS ######################

    /**
     * @return int
     */
    public function getId(){ return $this->id; }

    /**
     * @param bool $encode
     * @return string
     */
    public function getName(bool $encode = false) 
    { 
        return ($encode) ? Html::encode($this->name) : $this->name;
    }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int | null
     */
    public function getUpdatedAt() { return $this->updatedAt; }

    /**
     * @return bool | null
     */
    public function getDeleted() { return $this->deleted; }

    // #################### SECTION OF SETTERS ######################

    /**
     * @param string $value
     */
    public function setName(string $value) { $this->name = $value; }


    // #################### SECTION OF RELATIONS ######################

    /**
     * @return ProjectEntity[]
     */
    public function getProjects()
    {
        if($this->projects === null)
        {
            $this->projects = ProjectRepository::instance()->findAll(['company_id' => $this->getId()]);
        }

        return $this->projects;
    }

    /**
     * @return ParticipantEntity[]
     */
    public function getParticipants()
    {
        if($this->participants === null)
        {
            $this->participants = ParticipantRepository::instance()->findAll(['company_id' => $this->getId()]);
        }

        return $this->participants;
    }

    // #################### SECTION OF LOGIC ######################
}