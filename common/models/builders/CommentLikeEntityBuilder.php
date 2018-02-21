<?php

namespace common\models\builders;


use common\models\activerecords\CommentLike;
use common\models\entities\CommentLikeEntity;

class CommentLikeEntityBuilder
{
    /**
     * @return CommentLikeEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param CommentLike $model
     * @param CommentLikeEntity $commentLike
     */
    public function assignProperties(CommentLike &$model, CommentLikeEntity &$commentLike)
    {
        $model->comment_id = $commentLike->getCommentId();
        $model->user_id = $commentLike->getUserId();
        $model->liked = $commentLike->getLiked();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param CommentLike $model
     * @return CommentLikeEntity
     */
    public function buildEntity(CommentLike $model)
    {
        return new CommentLikeEntity(
            $model->comment_id,
            $model->user_id, 
            $model->liked,
            $model->id, 
            $model->created_at, 
            $model->updated_at
        );
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param CommentLike[] $models
     * @return CommentLikeEntity[]
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