<?php

namespace common\tests\models\repositories;


use common\models\entities\TaskLikeEntity;
use common\models\repositories\task\TaskLikeRepository;


class TaskLikeRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'taskId'     => 1,
        'userId'     => 1,
        'liked'      => true,
    ];

    /** @var array */
    protected $dataForSetters = [

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
        $taskLikeRepository = TaskLikeRepository::instance();

        $this->assertEquals($taskLikeRepository, new TaskLikeRepository());
    }

    public function testAdd()
    {
        /** @var TaskLikeEntity $taskLike */
        $taskLike = TaskLikeRepository::instance()->add(
            new TaskLikeEntity(
                $this->data['taskId'],
                $this->data['userId'],
                $this->data['liked']
            )
        );

        $this->tester->seeRecord($this->paths['taskLike'], ['id' => $taskLike->getId()]);
    }

    public function testUpdate()
    {
        /** @var TaskLikeEntity $taskLike */
        $taskLike = TaskLikeRepository::instance()->add(
            new TaskLikeEntity(
                $this->data['taskId'],
                $this->data['userId'],
                $this->data['liked']
            )
        );

        $taskLike->dislike();

        $this->assertEquals(TaskLikeRepository::instance()->update($taskLike), $taskLike);
    }

    public function testDelete()
    {
        /** @var TaskLikeEntity $taskLike */
        $taskLike = TaskLikeRepository::instance()->add(
            new TaskLikeEntity(
                $this->data['taskId'],
                $this->data['userId'],
                $this->data['liked']
            )
        );

        TaskLikeRepository::instance()->delete($taskLike);

        $this->tester->dontSeeRecord($this->paths['taskLike'], ['id' => $taskLike->getId()]);
    }
}