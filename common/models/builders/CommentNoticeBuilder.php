<?php

namespace common\models\builders;

use common\models\activerecords\CommentNotice;
use common\models\entities\CommentNoticeEntity;

/**
 * Class CommentNoticeBuilder
 * @package common\models\builders
 */
class CommentNoticeBuilder
{
    /**
     * @return CommentNoticeBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * @param CommentNotice $model
     * @param CommentNoticeEntity $commentNotice
     */
    public function assignProperties(CommentNotice &$model, CommentNoticeEntity &$commentNotice)
    {
        $model->comment_id = $commentNotice->getCommentId();
        $model->notice_id = $commentNotice->getNoticeId();
    }

    /**
     * @param CommentNotice $model
     * @return CommentNoticeEntity
     */
    public function buildEntity(CommentNotice $model)
    {
        $comment = null;
        $notice = null;

        if ($model->isRelationPopulated('comment')) {
            $comment = ($model->comment) ? CommentEntityBuilder::instance()->buildEntity($model->comment) : null;
        }

        if ($model->isRelationPopulated('notice')) {
            $notice = ($model->notice) ? NoticeEntityBuilder::instance()->buildEntity($model->notice) : null;
        }

        return new CommentNoticeEntity(
            $model->comment_id, 
            $model->notice_id, 
            $model->id,
            $comment,
            $notice
        );
    }

    /**
     * @param CommentNotice[] $models
     * @return CommentNoticeEntity[]
     */
    public function buildEntities(array $models)
    {
        if (!$models) {
            return [];
        }

        $entities = [];

        foreach ($models as $model) {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }
}