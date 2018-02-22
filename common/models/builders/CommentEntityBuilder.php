<?php

namespace common\models\builders;

use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;

/**
 * Class CommentEntityBuilder
 * @package common\models\builders
 */
class CommentEntityBuilder
{
    /**
     * @return CommentEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Comment $model
     * @param CommentEntity $comment
     */
    public function assignProperties(Comment &$model, CommentEntity &$comment)
    {
        $model->task_id = $comment->getTaskId();
        $model->sender_id = $comment->getSenderId();
        $model->content = $comment->getContent();
        $model->parent_id = $comment->getParentId();
        $model->private = $comment->getPrivate();
        $model->deleted = $comment->getDeleted();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param Comment $model
     * @return CommentEntity
     */
    public function buildEntity(Comment $model)
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
            $commentLikes
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Comment[] $models
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