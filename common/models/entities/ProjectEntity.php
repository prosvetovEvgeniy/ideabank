<?php

namespace common\models\entities;


use common\models\repositories\CompanyRepository;
use yii\db\ActiveRecord;

class ProjectEntity extends ActiveRecord
{
    protected $id;
    protected $name;
    protected $companyId;
    protected $defaultVisibilityArea;
    protected $createdAt;
    protected $updatedAt;


    public function __construct(string $name, int $companyId, int $id = null, int $defaultVisibilityArea = null,
                                int $createdAt = null, int $updatedAt = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->companyId = $companyId;
        $this->defaultVisibilityArea = $defaultVisibilityArea;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        parent::__construct();
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getCompanyId() { return $this->companyId; }
    public function getVisibilityArea() { return $this->defaultVisibilityArea; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getCompany() { return (new CompanyRepository())->get($this->companyId); }
}