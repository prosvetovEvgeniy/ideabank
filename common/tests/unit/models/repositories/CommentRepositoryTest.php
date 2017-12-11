<?php

namespace common\tests\models\repositories;

use common\models\entities\CommentEntity;
use common\models\repositories\CommentRepository;

class CommentRepositoryTest extends BaseRepositoryTest
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var array */
    protected $data = [
        'taskId'    => 1,
        'senderId'  => 1,
        'content'   => 'Hello World',
        'parentId' => null,
        'private'   => false,
    ];

    /** @var array */
    protected $dataForSetters = [
        'content'   => 'new Content',
        'parentId' => 1,
        'private'   => true,
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
        $commentRepository = CommentRepository::instance();

        $this->assertEquals($commentRepository, new CommentRepository());
    }

    public function testAdd()
    {
        /** @var CommentEntity $comment */
        $comment = CommentRepository::instance()->add(
            new CommentEntity(
                $this->data['taskId'],
                $this->data['senderId'],
                $this->data['content'],
                $this->data['parentId'],
                $this->data['private']
            )
        );

        $this->tester->seeRecord($this->paths['comment'], ['id' => $comment->getId()]);
    }

    public function testUpdate()
    {
        /** @var CommentEntity $comment */
        $comment = CommentRepository::instance()->add(
            new CommentEntity(
                $this->data['taskId'],
                $this->data['senderId'],
                $this->data['content'],
                $this->data['parentId'],
                $this->data['private']
            )
        );

        $comment->setContent($this->dataForSetters['content']);
        $comment->setParentId($this->dataForSetters['parentId']);
        $comment->setPrivate($this->dataForSetters['private']);

        $this->assertEquals(CommentRepository::instance()->update($comment), $comment);
    }

    public function testDelete()
    {
        /** @var CommentEntity $comment */
        $comment = CommentRepository::instance()->add(
            new CommentEntity(
                $this->data['taskId'],
                $this->data['senderId'],
                $this->data['content'],
                $this->data['parentId'],
                $this->data['private']
            )
        );

        CommentRepository::instance()->delete($comment);

        $this->tester->seeRecord($this->paths['comment'], ['id' => $comment->getId(), 'deleted' => true]);
    }
}