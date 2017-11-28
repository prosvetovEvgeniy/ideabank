<?php
namespace common\tests\models\entities;


use common\models\entities\CompanyEntity;
use common\models\entities\ProjectEntity;
use common\models\entities\TaskEntity;
use common\models\repositories\ProjectRepository;

class ProjectEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var ProjectEntity */
    protected $project;

    /** @var array */
    protected $data = [
        'id'                    => 1,
        'name'                  => 'project name',
        'companyId'             => 1,
        'defaultVisibilityArea' => 1,
        'createdAt'             => 1511431761,
        'updatedAt'             => 1511431761,
        'deleted'               => false
    ];

    /** @var array */
    protected $dataForSetters = [
        'name'                  => 'new Name',
        'companyId'             => 2,
        'defaultVisibilityArea' => 2
    ];

    protected function _before()
    {
        $this->project = new ProjectEntity(
            $this->data['name'],
            $this->data['companyId'],
            $this->data['id'],
            $this->data['defaultVisibilityArea'],
            $this->data['createdAt'],
            $this->data['updatedAt'],
            $this->data['deleted']
        );
    }

    protected function _after()
    {
    }


    // #################### TESTS OF GETTERS ######################

    public function testGetId()
    {
        $this->assertEquals($this->project->getId(), $this->data['id']);
    }

    public function testGetName()
    {
        $this->assertEquals($this->project->getName(), $this->data['name']);
    }

    public function testGetCompanyId()
    {
        $this->assertEquals($this->project->getCompanyId(), $this->data['companyId']);
    }

    public function testGetDefaultVisibilityArea()
    {
        $this->assertEquals($this->project->getDefaultVisibilityArea(), $this->data['defaultVisibilityArea']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->project->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->project->getUpdatedAt(), $this->data['updatedAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->project->getDeleted(), $this->data['deleted']);
    }

    // #################### TESTS OF SETTERS ######################

    public function testSetName()
    {
        $this->project->setName($this->dataForSetters['name']);

        $this->assertEquals($this->project->getName(), $this->dataForSetters['name']);
    }

    public function testSetCompanyId()
    {
        $this->project->setCompanyId($this->dataForSetters['companyId']);

        $this->assertEquals($this->project->getCompanyId(), $this->dataForSetters['companyId']);
    }

    public function testSetDefaultVisibilityArea()
    {
        $this->project->setDefaultVisibilityArea($this->dataForSetters['defaultVisibilityArea']);

        $this->assertEquals($this->project->getDefaultVisibilityArea(), $this->dataForSetters['defaultVisibilityArea']);
    }

    // #################### TESTS OF RELATIONS ######################

    public function testGetCompanyCheckOnClassName()
    {
        $project = ProjectRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(get_class($project->getCompany()), CompanyEntity::class);
    }

    public function testGetTasksCheckOnArray()
    {
        $project = ProjectRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(is_array($project->getTasks()), true);
    }

    public function testGetTasksCheckOnClassName()
    {
        $project = ProjectRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(get_class($project->getTasks()[0]), TaskEntity::class);
    }

    // #################### TESTS OF LOGIC ######################
}