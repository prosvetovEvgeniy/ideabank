<?php
namespace common\tests\models\entities;


use common\models\entities\MessageEntity;
use common\models\entities\UserEntity;
use common\models\repositories\message\MessageRepository;

class MessageEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var  MessageEntity */
    protected $message;

    /** @var array */
    protected $data = [
        'id'          => 1,
        'selfId'      => 1,
        'companionId' => 1,
        'content'     => 'Hello World',
        'isSender'    => true,
        'createdAt'   => 1511431761,
        'deleted'     => 1511431761,
    ];

    /** @var array */
    protected $dataForSetters = [
        'companionId' => 2
    ];

    protected function _before()
    {
        $this->message = new MessageEntity(
            $this->data['selfId'],
            $this->data['companionId'],
            $this->data['isSender'],
            $this->data['content'],
            $this->data['id'],
            $this->data['createdAt'],
            $this->data['deleted']
        );
    }

    protected function _after()
    {
    }


    // #################### TESTS OF GETTERS ######################

    public function testGetId()
    {
        $this->assertEquals($this->message->getId(), $this->data['id']);
    }

    public function testGetSelfId()
    {
        $this->assertEquals($this->message->getSelfId(), $this->data['selfId']);
    }

    public function testGetCompanionId()
    {
        $this->assertEquals($this->message->getCompanionId(), $this->data['companionId']);
    }

    public function testGetIsSender()
    {
        $this->assertEquals($this->message->getIsSender(), $this->data['isSender']);
    }

    public function testGetContent()
    {
        $this->assertEquals($this->message->getContent(), $this->data['content']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->message->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->message->getDeleted(), $this->data['deleted']);
    }


    // #################### TESTS OF SETTERS ######################

    public function testSetCompanionId()
    {
        $this->message->setCompanionId($this->dataForSetters['companionId']);

        $this->assertEquals($this->message->getCompanionId(), $this->dataForSetters['companionId']);
    }


    // #################### TESTS OF RELATIONS ######################

    public function testGetSelfCheckOnClassName()
    {
        $message = MessageRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($message->getSelf()), UserEntity::class);
    }

    public function testGetCompanionCheckOnClassName()
    {
        $message = MessageRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($message->getCompanion()), UserEntity::class);
    }


    // #################### TESTS OF LOGIC ######################
}