<?php

namespace common\tests\models\repositories;


use common\models\entities\CommentLikeEntity;
use common\models\repositories\CommentLikeRepository;


class CommentLikeRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'commentId'  => 1,
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
        $commentLikeRepository = CommentLikeRepository::instance();

        $this->assertEquals($commentLikeRepository, new CommentLikeRepository());
    }

    public function testAdd()
    {
        /** @var CommentLikeEntity $commentLike */
        $commentLike = CommentLikeRepository::instance()->add(
            new CommentLikeEntity(
                $this->data['commentId'],
                $this->data['userId'],
                $this->data['liked']
            )
        );

        $this->tester->seeRecord($this->paths['commentLike'], ['id' => $commentLike->getId()]);
    }

    public function testUpdate()
    {
        /** @var CommentLikeEntity $commentLike */
        $commentLike = CommentLikeRepository::instance()->add(
            new CommentLikeEntity(
                $this->data['commentId'],
                $this->data['userId'],
                $this->data['liked']
            )
        );

        $commentLike->dislike();

        $this->assertEquals(CommentLikeRepository::instance()->update($commentLike), $commentLike);
    }

    public function testDelete()
    {
        /** @var CommentLikeEntity $commentLike */
        $commentLike = CommentLikeRepository::instance()->add(
            new CommentLikeEntity(
                $this->data['commentId'],
                $this->data['userId'],
                $this->data['liked']
            )
        );

        CommentLikeRepository::instance()->delete($commentLike);

        $this->tester->dontSeeRecord($this->paths['commentLike'], ['id' => $commentLike->getId()]);
    }
}