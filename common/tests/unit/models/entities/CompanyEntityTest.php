<?php
namespace common\tests\models\entities;

use common\models\entities\CompanyEntity;
use common\models\entities\ParticipantEntity;
use common\models\entities\ProjectEntity;
use common\models\repositories\company\CompanyRepository;

class CompanyEntityTest extends \Codeception\Test\Unit
{
    /** @var \common\tests\UnitTester */
    protected $tester;

    /** @var  CompanyEntity */
    protected $company;

    /** @var array */
    protected $data = [
        'id'        => 0,
        'name'      => 'testName',
        'createdAt' => 1511431761,
        'updatedAt' => 1511431761,
        'deleted'   => false
    ];

    /** @var array */
    protected $dataForSetters = [
        'name'      => 'newName'
    ];

    protected function _before()
    {
        $this->company = new CompanyEntity(
            $this->data['name'],
            $this->data['id'],
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
        $this->assertEquals($this->company->getId(), $this->data['id']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->company->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetName()
    {
        $this->assertEquals($this->company->getName(), $this->data['name']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->company->getUpdatedAt(), $this->data['updatedAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->company->getDeleted(), $this->data['deleted']);
    }


    // #################### TESTS OF SETTERS ######################

    public function testSetName()
    {
        $this->company->setName($this->dataForSetters['name']);

        $this->assertEquals($this->company->getName(), $this->dataForSetters['name']);
    }


    // #################### TESTS OF RELATIONS ######################

    public function testGetProjectsCheckOnArray()
    {
        $company = CompanyRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($company->getProjects()), true);
    }

    public function testGetProjectsCheckOnClassName()
    {
        $company = CompanyRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($company->getProjects()[0]), ProjectEntity::class);
    }

    public function testGetParticipantsCheckOnArray()
    {
        $company = CompanyRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($company->getParticipants()), true);
    }

    public function testGetParticipantsCheckOnClassName()
    {
        $company = CompanyRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($company->getParticipants()[0]), ParticipantEntity::class);
    }

    // #################### TESTS OF LOGIC ######################

}






















