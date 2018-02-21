<?php

namespace common\models\builders;

use common\models\activerecords\CommentNotice;
use common\models\entities\CommentNoticeEntity;

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
        return new CommentNoticeEntity(
            $model->comment_id, 
            $model->notice_id, 
            $model->id
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