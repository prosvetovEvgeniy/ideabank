<?php

namespace common\components\facades;


use common\models\entities\CommentEntity;
use common\models\repositories\comment\CommentRepository;
use yii\db\Exception;

/**
 * Class CommentFacade
 * @package common\components\facades
 *
 * @property CommentNoticeFacade $commentNoticeFacade
 */
class CommentFacade
{
    private $commentNoticeFacade;

    /**
     * CommentFacade constructor.
     */
    public function __construct()
    {
        $this->commentNoticeFacade = new CommentNoticeFacade();
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function createComment(CommentEntity $comment)
    {
        $comment = CommentRepository::instance()->add($comment);

        $this->commentNoticeFacade->saveNotices($comment);

        return $comment;
    }


    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function editComment(CommentEntity $comment)
    {
        $this->commentNoticeFacade->deleteAndSaveNotices($comment);
        
        return CommentRepository::instance()->update($comment);
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function deleteComment(CommentEntity $comment)
    {
        $this->commentNoticeFacade->deleteNotices($comment);

        return CommentRepository::instance()->delete($comment);
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function reestablishComment(CommentEntity $comment)
    {
        $comment->setDeleted(false);
        CommentRepository::instance()->update($comment);

        $this->commentNoticeFacade->saveNotices($comment);

        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function makePrivate(CommentEntity $comment)
    {
        $comment->setPrivate(true);
        CommentRepository::instance()->update($comment);

        $this->commentNoticeFacade->deleteNotices($comment);
        $this->commentNoticeFacade->savePrivateNotices($comment);

        return $comment;
    }

    /**
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function makePublic(CommentEntity $comment)
    {
        $comment->setPrivate(false);
        CommentRepository::instance()->update($comment);

        $this->commentNoticeFacade->deleteAndSaveNotices($comment);

        return $comment;
    }
}