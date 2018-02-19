<?php

namespace common\models\builders;

use common\models\activerecords\CommentView;
use common\models\entities\CommentEntity;

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
     * Создает экземпляр сущности
     *
     * @param CommentView $model
     * @return CommentEntity
     */
    public function buildEntity(CommentView $model)
    {
        $userEntity = UserEntityBuilder::instance()->buildEntity($model->user);

        //если у комментария нет родителя
        $parentCommentEntity = ($model->parent) ? CommentEntityBuilder::instance()->buildEntity($model->parent) : null;

        return new CommentEntity($model->task_id, $model->sender_id,$model->content, $model->parent_id,
                                 $model->private, $model->id, $model->created_at, $model->updated_at,
                                 $model->deleted, $model->likes_amount, $model->dislikes_amount,
                                 $userEntity, $parentCommentEntity, $model->current_user_liked_it,
                                 $model->current_user_disliked_it);
    }


    /**
     * Создает экземпляры сущностей
     *
     * @param CommentView[] $models
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