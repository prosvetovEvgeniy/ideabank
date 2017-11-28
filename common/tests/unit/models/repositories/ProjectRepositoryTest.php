<?php

namespace common\tests\models\repositories;


use common\models\entities\ProjectEntity;
use common\models\repositories\ProjectRepository;

class ProjectRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'name'                  => 'project name',
        'companyId'             => 1,
        'defaultVisibilityArea' => 1,
    ];

    /** @var array */
    protected $dataForSetters = [
        'name'                  => 'new Name',
        'companyId'             => 2,
        'defaultVisibilityArea' => 2
    ];

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInstance()
    {
        $projectRepository = ProjectRepository::instance();

        $this->assertEquals($projectRepository, new ProjectRepository());
    }

    public function testAdd()
    {
        /** @var ProjectEntity $project */
        $project = ProjectRepository::instance()->add(
            new ProjectEntity(
                $this->data['name'],
                $this->data['companyId'],
                $this->data['defaultVisibilityArea']
            )
        );

        $this->tester->seeRecord($this->paths['project'], ['id' => $project->getId()]);
    }

    public function testUpdate()
    {
        /** @var ProjectEntity $project */
        $project = ProjectRepository::instance()->add(
            new ProjectEntity(
                $this->data['name'],
                $this->data['companyId'],
                $this->data['defaultVisibilityArea']
            )
        );

        $project->setCompanyId($this->dataForSetters['companyId']);
        $project->setName($this->dataForSetters['name']);
        $project->setDefaultVisibilityArea($this->dataForSetters['defaultVisibilityArea']);

        $this->assertEquals(ProjectRepository::instance()->update($project), $project);
    }

    public function testDelete()
    {
        /** @var ProjectEntity $project */
        $project = ProjectRepository::instance()->add(
            new ProjectEntity(
                $this->data['name'],
                $this->data['companyId'],
                $this->data['defaultVisibilityArea']
            )
        );

        ProjectRepository::instance()->delete($project);

        $this->tester->seeRecord($this->paths['project'], ['id' => $project->getId(), 'deleted' => true]);
    }
}