<?php
namespace common\tests\models\entities;


use common\models\entities\TaskLikeEntity;

class TaskLikeEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var TaskLikeEntity */
    protected $taskLike;

    /** @var array */
    protected $data = [
        'id'         => 1,
        'taskId'     => 1,
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
        $this->taskLike = new TaskLikeEntity(
            $this->data['taskId'],
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
        $this->assertEquals($this->taskLike->getId(), $this->data['id']);
    }

    public function testGetCommentId()
    {
        $this->assertEquals($this->taskLike->getTaskId(), $this->data['taskId']);
    }

    public function testGetUserId()
    {
        $this->assertEquals($this->taskLike->getUserId(), $this->data['userId']);
    }

    public function testGetLiked()
    {
        $this->assertEquals($this->taskLike->getLiked(), $this->data['liked']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->taskLike->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->taskLike->getUpdatedAt(), $this->data['updatedAt']);
    }
    

    // #################### TESTS OF SETTERS ######################



    // #################### TESTS OF RELATIONS ######################



    // #################### TESTS OF LOGIC ######################

    public function testLike()
    {
        $this->taskLike->like();
        $this->assertEquals($this->taskLike->getLiked(), true);
    }

    public function testDislike()
    {
        $this->taskLike->dislike();
        $this->assertEquals($this->taskLike->getLiked(), false);
    }
}