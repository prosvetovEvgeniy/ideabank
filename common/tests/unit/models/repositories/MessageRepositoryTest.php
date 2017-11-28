<?php

namespace common\tests\models\repositories;

use common\models\entities\MessageEntity;
use common\models\repositories\MessageRepository;

class MessageRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'selfId'      => 1,
        'companionId' => 1,
        'content'     => 'Hello World',
        'isSender'    => true,
    ];

    /** @var array */
    protected $dataForSetters = [
        'companionId' => 2
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
        $messageRepository = MessageRepository::instance();

        $this->assertEquals($messageRepository, new MessageRepository());
    }

    public function testAdd()
    {
        /** @var MessageEntity $message */
        $message = MessageRepository::instance()->add(
            new MessageEntity(
                $this->data['selfId'],
                $this->data['companionId'],
                $this->data['isSender'],
                $this->data['content']
            )
        );

        $this->tester->seeRecord($this->paths['message'], ['id' => $message->getId()]);
    }

    public function testUpdate()
    {
        /** @var MessageEntity $message */
        $message = MessageRepository::instance()->add(
            new MessageEntity(
                $this->data['selfId'],
                $this->data['companionId'],
                $this->data['isSender'],
                $this->data['content']
            )
        );

        $message->setCompanionId($this->dataForSetters['companionId']);

        $this->assertEquals(MessageRepository::instance()->update($message), $message);
    }

    public function testDelete()
    {
        /** @var MessageEntity $message */
        $message = MessageRepository::instance()->add(
            new MessageEntity(
                $this->data['selfId'],
                $this->data['companionId'],
                $this->data['isSender'],
                $this->data['content']
            )
        );

        MessageRepository::instance()->delete($message);

        $this->tester->seeRecord($this->paths['message'], ['id' => $message->getId(), 'deleted' => true]);
    }
}