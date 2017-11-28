<?php
namespace common\tests\models\entities;


use common\models\entities\CommentEntity;
use common\models\entities\CommentLikeEntity;
use common\models\entities\UserEntity;
use common\models\repositories\CommentLikeRepository;

class CommentLikeEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var  CommentLikeEntity */
    protected $commentLike;

    /** @var array */
    protected $data = [
        'id'         => 1,
        'commentId'  => 1,
        'userId'     => 1,
        'liked'      => true,
        'createdAt'  => 1511431761,
        'updatedAt'  => 1511431761,
    ];

    /** @var array */
    protected $dataForSetters = [

    ];

    protected function _before()
    {
        $this->commentLike = new CommentLikeEntity(
            $this->data['commentId'],
            $this->data['userId'],
            $this->data['liked'],
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
        $this->assertEquals($this->commentLike->getId(), $this->data['id']);
    }

    public function testGetCommentId()
    {
        $this->assertEquals($this->commentLike->getCommentId(), $this->data['commentId']);
    }

    public function testGetUserId()
    {
        $this->assertEquals($this->commentLike->getUserId(), $this->data['userId']);
    }

    public function testGetLiked()
    {
        $this->assertEquals($this->commentLike->getLiked(), $this->data['liked']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->commentLike->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->commentLike->getUpdatedAt(), $this->data['updatedAt']);
    }

    // #################### TESTS OF SETTERS ######################


    // #################### TESTS OF RELATIONS ######################

    public function testGetCommentCheckOnClassName()
    {
        $commentLike = CommentLikeRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($commentLike->getComment()), CommentEntity::class);
    }

    public function testGetTaskCheckOnClassName()
    {
        $commentLike = CommentLikeRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($commentLike->getUser()), UserEntity::class);
    }


    // #################### TESTS OF LOGIC ######################

    public function testLike()
    {
        $this->commentLike->like();
        $this->assertEquals($this->commentLike->getLiked(), true);
    }

    public function testDislike()
    {
        $this->commentLike->dislike();
        $this->assertEquals($this->commentLike->getLiked(), false);
    }
}

























