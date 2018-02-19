<?php

namespace common\models\builders;

use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use yii\helpers\Html;

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
        $model->content = Html::encode($comment->getContent());
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
        return new CommentEntity($model->task_id, $model->sender_id,$model->content, $model->parent_id,
                                 $model->private, $model->id, $model->created_at, $model->updated_at,
                                 $model->deleted);
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