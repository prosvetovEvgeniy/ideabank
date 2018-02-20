<?php

namespace common\models\entities;

use common\models\interfaces\INotice;
use common\models\repositories\comment\CommentRepository;
use common\models\interfaces\IEntity;
use common\models\repositories\notice\NoticeRepository;

/**
 * Class TaskNoticeEntity
 * @package common\models\entities
 *
 * @property integer $id
 * @property integer $commentId
 * @property integer $noticeId
 * @property CommentEntity $comment
 * @property NoticeEntity $notice
 */
class CommentNoticeEntity implements IEntity, INotice
{
    private $id;
    private $commentId;
    private $noticeId;

    //кеш связанных сущностей
    private $comment;
    private $notice;


    /**
     * CommentNoticeEntity constructor.
     * @param int $commentId
     * @param int $noticeId
     * @param int|null $id
     */
    public function __construct(int $commentId, int $noticeId, int $id = null)
    {
        $this->id = $id;
        $this->commentId = $commentId;
        $this->noticeId = $noticeId;
    }

    /**
     * @return int
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getCommentId() { return $this->commentId; }

    /**
     * @return int
     */
    public function getNoticeId() { return $this->noticeId; }

    /**
     * @return CommentEntity|IEntity|null
     */
    public function getComment()
    {
        if ($this->comment === null) {
            $this->comment = CommentRepository::instance()->findOne(['id' => $this->commentId]);
        }

        return $this->comment;
    }

    /**
     * @return NoticeEntity|null
     */
    public function getNotice()
    {
        if ($this->notice === null) {
            $this->notice = NoticeRepository::instance()->findOne(['id' => $this->noticeId]);
        }

        return $this->notice;
    }
}