<?php
namespace common\tests\models\entities;


use common\models\entities\NoticeEntity;
use common\models\entities\UserEntity;
use common\models\repositories\NoticeRepository;

class NoticeEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var NoticeEntity */
    protected $notice;

    /** @var array */
    protected $data = [
        'id'          => 1,
        'recipientId' => 1,
        'content'     => 'Hello World',
        'createdAt'   => 1511431761,
        'viewed'      => false
    ];

    /** @var array */
    protected $dataForSetters = [
        'recipientId' => 2,
        'content'     => 'new Content'
    ];

    protected function _before()
    {
        $this->notice = new NoticeEntity(
            $this->data['recipientId'],
            $this->data['content'],
            $this->data['id'],
            $this->data['createdAt'],
            $this->data['viewed']
        );
    }

    protected function _after()
    {
    }

    // #################### TESTS OF GETTERS ######################

    public function testGet()
    {
        $this->assertEquals($this->notice->getId(), $this->data['id']);
    }

    public function testGetRecipientId()
    {
        $this->assertEquals($this->notice->getRecipientId(), $this->data['recipientId']);
    }

    public function testGetContent()
    {
        $this->assertEquals($this->notice->getContent(), $this->data['content']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->notice->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->notice->getViewed(), $this->data['viewed']);
    }

    // #################### TESTS OF SETTERS ######################

    public function testSetRecipientId()
    {
        $this->notice->setRecipientId($this->dataForSetters['recipientId']);

        $this->assertEquals($this->notice->getRecipientId(), $this->dataForSetters['recipientId']);
    }

    public function testSetContent()
    {
        $this->notice->setContent($this->dataForSetters['content']);

        $this->assertEquals($this->notice->getContent(), $this->dataForSetters['content']);
    }

    // #################### TESTS OF RELATIONS ######################

    public function testGetUserCheckOnClassName()
    {
        $notice = NoticeRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($notice->getUser()), UserEntity::class);
    }


    // #################### TESTS OF LOGIC ######################
}