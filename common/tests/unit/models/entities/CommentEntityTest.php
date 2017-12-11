<?php
namespace common\tests\models\entities;


use common\models\entities\CommentEntity;
use common\models\entities\CommentLikeEntity;
use common\models\entities\TaskEntity;
use common\models\entities\UserEntity;
use common\models\repositories\CommentRepository;

class CommentEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var  CommentEntity */
    protected $comment;

    /** @var array */
    protected $data = [
        'id'        => 1,
        'taskId'    => 1,
        'senderId'  => 1,
        'content'   => 'Hello World',
        'parentId' => null,
        'private'   => false,
        'createdAt' => 1511431761,
        'updatedAt' => 1511431761,
        'deleted'   => false
    ];

    /** @var array */
    protected $dataForSetters = [
        'content'   => 'new Content',
        'parentId' => 1,
        'private'   => true,
    ];

    protected function _before()
    {
        $this->comment = new CommentEntity(
            $this->data['taskId'],
            $this->data['senderId'],
            $this->data['content'],
            $this->data['parentId'],
            $this->data['private'],
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
        $this->assertEquals($this->comment->getId(), $this->data['id']);
    }

    public function testGetTaskId()
    {
        $this->assertEquals($this->comment->getTaskId(), $this->data['taskId']);
    }

    public function testGetSenderId()
    {
        $this->assertEquals($this->comment->getSenderId(), $this->data['senderId']);
    }

    public function testGetContent()
    {
        $this->assertEquals($this->comment->getContent(), $this->data['content']);
    }

    public function testGetParentId()
    {
        $this->assertEquals($this->comment->getParentId(), $this->data['parentId']);
    }

    public function testGetPrivate()
    {
        $this->assertEquals($this->comment->getPrivate(), $this->data['private']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->comment->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->comment->getUpdatedAt(), $this->data['updatedAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->comment->getDeleted(), $this->data['deleted']);
    }


    // #################### TESTS OF SETTERS ######################

    public function testSetContent()
    {
        $this->comment->setContent($this->dataForSetters['content']);

        $this->assertEquals($this->comment->getContent(), $this->dataForSetters['content']);
    }

    public function testSetParentId()
    {
        $this->comment->setParentId($this->dataForSetters['parentId']);

        $this->assertEquals($this->comment->getParentId(), $this->dataForSetters['parentId']);
    }

    public function testSetPrivate()
    {
        $this->comment->setPrivate($this->dataForSetters['private']);

        $this->assertEquals($this->comment->getPrivate(), $this->dataForSetters['private']);
    }


    // #################### TESTS OF RELATIONS ######################

    public function testGetParentCheckOnClassName()
    {
        $comment = CommentRepository::instance()->findOne(['id' => 2]);

        $this->assertEquals(get_class($comment->getParent()), CommentEntity::class);
    }

    public function testGetTaskCheckOnClassName()
    {
        $comment = CommentRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($comment->getTask()), TaskEntity::class);
    }

    public function testGetUserCheckOnClassName()
    {
        $comment = CommentRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($comment->getUser()), UserEntity::class);
    }

    public function testGetCommentLikesCheckOnArray()
    {
        $comment = CommentRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($comment->getCommentLikes()), true);
    }

    public function testGetCommentLikesCheckOnClassName()
    {
        $comment = CommentRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($comment->getCommentLikes()[0]), CommentLikeEntity::class);
    }

    // #################### TESTS OF LOGIC ######################
}
































