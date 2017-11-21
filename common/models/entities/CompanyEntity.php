<?php

namespace common\models\entities;

use common\models\repositories\ProjectRepository;
use yii\db\ActiveRecord;


class CompanyEntity extends ActiveRecord
{
    private $id;
    private $name;
    private $createdAt;
    private $updatedAt;

    /**
     * @var ProjectEntity[]
     */
    private $projects;

    public function __construct(string $name, int $id = null, int $createdAt = null, int $updatedAt = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        parent::__construct();
    }

    public function getId(){ return $this->id; }
    public function getName() { return $this->name; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getProjects() { return (new ProjectRepository())->getByCompanyId($this->id); }
}