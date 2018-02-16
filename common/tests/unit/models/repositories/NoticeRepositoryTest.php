<?php

namespace common\tests\models\repositories;


use common\models\entities\NoticeEntity;
use common\models\repositories\notice\NoticeRepository;

class NoticeRepositoryTest extends BaseRepositoryTest
{
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
    }

    protected function _after()
    {
    }

    // tests
    public function testInstance()
    {
        $noticeRepository = NoticeRepository::instance();

        $this->assertEquals($noticeRepository, new NoticeRepository());
    }

    public function testAdd()
    {
        /** @var NoticeEntity $notice */
        $notice = NoticeRepository::instance()->add(
            new NoticeEntity(
                $this->data['recipientId'],
                $this->data['content']
            )
        );

        $this->tester->seeRecord($this->paths['notice'], ['id' => $notice->getId()]);
    }

    public function testUpdate()
    {
        /** @var NoticeEntity $notice */
        $notice = NoticeRepository::instance()->add(
            new NoticeEntity(
                $this->data['recipientId'],
                $this->data['content']
            )
        );

        $notice->setRecipientId($this->dataForSetters['recipientId']);
        $notice->setContent($this->dataForSetters['content']);

        $this->assertEquals(NoticeRepository::instance()->update($notice), $notice);
    }

    public function testDelete()
    {
        /** @var NoticeEntity $notice */
        $notice = NoticeRepository::instance()->add(
            new NoticeEntity(
                $this->data['recipientId'],
                $this->data['content']
            )
        );

        NoticeRepository::instance()->delete($notice);

        $this->tester->seeRecord($this->paths['notice'], ['id' => $notice->getId(), 'viewed' => true]);
    }
}