<?php

namespace common\models\builders;

use common\models\activerecords\CommentView;
use common\models\entities\CommentEntity;

/**
 * Class CommentViewEntityBuilder
 * @package common\models\builders
 */
class CommentViewEntityBuilder
{
    /**
     * @return CommentViewEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * @param CommentView $model
     * @return CommentEntity
     */
    public function buildEntity(CommentView $model)
    {
        $sender = null;
        $parent = null;
        $task = null;
        $commentLikes = null;

        if ($model->isRelationPopulated('sender')) {
            $sender = ($model->sender) ? UserEntityBuilder::instance()->buildEntity($model->sender) : null;
        }

        if ($model->isRelationPopulated('parent')) {
            $parent = ($model->parent) ? CommentEntityBuilder::instance()->buildEntity($model->parent) : null;
        }

        if ($model->isRelationPopulated('task')) {
            $task = ($model->task) ? TaskEntityBuilder::instance()->buildEntity($model->task) : null;
        }

        if ($model->isRelationPopulated('commentLikes')) {
            $commentLikes = ($model->commentLikes) ? CommentLikeEntityBuilder::instance()->buildEntities($model->commentLikes) : null;
        }

        return new CommentEntity(
            $model->task_id,
            $model->sender_id,
            $model->content,
            $model->parent_id,
            $model->private,
            $model->id,
            $model->created_at,
            $model->updated_at,
            $model->deleted,
            $sender,
            $parent,
            $task,
            $commentLikes,
            $model->likes_amount,
            $model->dislikes_amount,
            $model->current_user_liked_it,
            $model->current_user_disliked_it
        );
    }


    /**
     * @param array $models
     * @return CommentEntity[]
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