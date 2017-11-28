<?php
namespace common\tests\models\entities;


use common\models\entities\CompanyEntity;
use common\models\entities\ParticipantEntity;
use common\models\entities\ProjectEntity;
use common\models\entities\UserEntity;
use common\models\repositories\ParticipantRepository;

class ParticipantEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var ParticipantEntity */
    protected $participant;

    /** @var array */
    protected $data = [
        'id'          => 1,
        'userId'      => 1,
        'companyId'   => 1,
        'projectId'   => 1,
        'approved'    => false,
        'approvedAt'  => 1511431761,
        'blocked'     => false,
        'blockedAt'   => 1511431761,
        'createdAt'   => 1511431761,
        'updatedAt'   => 1511431761
    ];

    /** @var array */
    protected $dataForSetters = [
        'companyId'   => 2,
        'projectId'   => 2,
        'approved'    => true,
        'approvedAt'  => 1511431999
    ];

    protected function _before()
    {
        $this->participant = new ParticipantEntity(
            $this->data['userId'],
            $this->data['companyId'],
            $this->data['projectId'],
            $this->data['approved'],
            $this->data['approvedAt'],
            $this->data['blocked'],
            $this->data['blockedAt'],
            $this->data['id'],
            $this->data['createdAt'],
            $this->data['updatedAt']
        );
    }

    protected function _after()
    {
    }


    // #################### TESTS OF GETTERS ######################

    public function testGetId()
    {
        $this->assertEquals($this->participant->getId(), $this->data['id']);
    }

    public function testGetUserId()
    {
        $this->assertEquals($this->participant->getUserId(), $this->data['userId']);
    }

    public function testGetCompanyId()
    {
        $this->assertEquals($this->participant->getCompanyId(), $this->data['companyId']);
    }

    public function testGetProjectId()
    {
        $this->assertEquals($this->participant->getProjectId(), $this->data['projectId']);
    }

    public function testGetApproved()
    {
        $this->assertEquals($this->participant->getApproved(), $this->data['approved']);
    }

    public function testGetApprovedAt()
    {
        $this->assertEquals($this->participant->getApprovedAt(), $this->data['approvedAt']);
    }

    public function testGetBlocked()
    {
        $this->assertEquals($this->participant->getBlocked(), $this->data['blocked']);
    }

    public function testGetBlockedAt()
    {
        $this->assertEquals($this->participant->getBlockedAt(), $this->data['blockedAt']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->participant->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->participant->getUpdatedAt(), $this->data['updatedAt']);
    }

    // #################### TESTS OF SETTERS ######################

    public function testSetCompanyId()
    {
        $this->participant->setCompanyId($this->dataForSetters['companyId']);

        $this->assertEquals($this->participant->getCompanyId(), $this->dataForSetters['companyId']);
    }

    public function testSetProjectId()
    {
        $this->participant->setProjectId($this->dataForSetters['projectId']);

        $this->assertEquals($this->participant->getProjectId(), $this->dataForSetters['projectId']);
    }

    public function testSetApproved()
    {
        $this->participant->setApproved($this->dataForSetters['approved']);

        $this->assertEquals($this->participant->getApproved(), $this->dataForSetters['approved']);
    }

    public function testSetApprovedAt()
    {
        $this->participant->setApprovedAt($this->dataForSetters['approvedAt']);

        $this->assertEquals($this->participant->getApprovedAt(), $this->dataForSetters['approvedAt']);
    }


    // #################### TESTS OF RELATIONS ######################

    public function testGetUserCheckOnClassName()
    {
        $participant = ParticipantRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(get_class($participant->getUser()), UserEntity::class);
    }

    public function testGetProjectCheckOnClassName()
    {
        $participant = ParticipantRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(get_class($participant->getProject()), ProjectEntity::class);

    }

    public function testGetCompanyCheckOnClassName()
    {
        $participant = ParticipantRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(get_class($participant->getCompany()), CompanyEntity::class);

    }

    // #################### TESTS OF LOGIC ######################
}