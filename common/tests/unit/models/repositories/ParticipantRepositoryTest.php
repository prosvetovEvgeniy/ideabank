<?php

namespace common\tests\models\repositories;


use common\models\entities\ParticipantEntity;
use common\models\repositories\ParticipantRepository;


class ParticipantRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'userId'      => 1,
        'companyId'   => 1,
        'projectId'   => 1,
        'approved'    => false,
        'approvedAt'  => 1511431761,
        'blocked'     => false,
        'blockedAt'   => 1511431761,
    ];

    /** @var array */
    protected $dataForSetters = [
        'companyId'   => 2,
        'projectId'   => 2,
        'approved'    => true,
        'approvedAt'  => 1511431761
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
        $participantRepository = ParticipantRepository::instance();

        $this->assertEquals($participantRepository, new ParticipantRepository());
    }

    public function testAdd()
    {
        /** @var ParticipantEntity $participant */
        $participant = ParticipantRepository::instance()->add(
            new ParticipantEntity(
                $this->data['userId'],
                $this->data['companyId'],
                $this->data['projectId'],
                $this->data['approved'],
                $this->data['approvedAt'],
                $this->data['blocked'],
                $this->data['blockedAt']
            )
        );

        $this->tester->seeRecord($this->paths['participant'], ['id' => $participant->getId()]);
    }

    public function testUpdate()
    {
        /** @var ParticipantEntity $participant */
        $participant = ParticipantRepository::instance()->add(
            new ParticipantEntity(
                $this->data['userId'],
                $this->data['companyId'],
                $this->data['projectId'],
                $this->data['approved'],
                $this->data['approvedAt'],
                $this->data['blocked'],
                $this->data['blockedAt']
            )
        );

        $participant->setProjectId($this->dataForSetters['projectId']);
        $participant->setCompanyId($this->dataForSetters['companyId']);
        $participant->setApproved($this->dataForSetters['approved']);
        $participant->setApprovedAt($this->dataForSetters['approvedAt']);

        $this->assertEquals(ParticipantRepository::instance()->update($participant), $participant);
    }

    public function testDelete()
    {
        /** @var ParticipantEntity $participant */
        $participant = ParticipantRepository::instance()->add(
            new ParticipantEntity(
                $this->data['userId'],
                $this->data['companyId'],
                $this->data['projectId'],
                $this->data['approved'],
                $this->data['approvedAt'],
                $this->data['blocked'],
                $this->data['blockedAt']
            )
        );

        ParticipantRepository::instance()->block($participant);

        $this->tester->seeRecord($this->paths['participant'], ['id' => $participant->getId(), 'blocked' => true]);
    }
}