<?php

namespace common\models\repositories;


use Yii;
use common\models\activerecords\CommentView;
use common\models\entities\CommentEntity;
use common\models\activerecords\Comment;

/**
 * Этот репозиторий используется
 * только для отображения комментириев
 * с помощью жадной загрузки
 *
 * Class CommentViewRepository
 * @package common\models\repositories
 */
class CommentViewRepository
{
    public const COMMENTS_PER_PAGE = 30;

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return CommentViewRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string $orderBy
     * @return CommentEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $identity = Yii::$app->user->identity;

        /*
         * если пользователь не зарегистрирован, то он и не оставлял
         * комментарий => поле current_user_liked_it будет FALSE
         * (данное поле используется на frontend). Аналогично
         * для поля current_user_disliked_it
         */
        $userId = ($identity !== null) ? $identity->getId() : 'NULL';

        $models = CommentView::find()->where($condition)
                                     ->with('user')
                                     ->with('parent')
                                     ->joinWith('commentLikes')
                                     ->addSelect('comment.*')
                                     ->addSelect('SUM(CASE WHEN comment_like.liked = TRUE THEN 1 ELSE 0 END) AS likes_amount')
                                     ->addSelect('SUM(CASE WHEN comment_like.liked = FALSE THEN 1 ELSE 0 END) AS dislikes_amount')
                                     ->addSelect('(CASE WHEN comment_like.user_id = ' . $userId . ' AND comment_like.liked = TRUE THEN TRUE ELSE FALSE END) as current_user_liked_it')
                                     ->addSelect('(CASE WHEN comment_like.user_id = ' . $userId . ' AND comment_like.liked = FALSE THEN TRUE ELSE FALSE END) as current_user_disliked_it')
                                     ->groupBy('comment.id, comment_like.user_id, comment_like.liked')
                                     ->orderBy('id ASC')
                                     ->limit($limit)
                                     ->offset($offset)
                                     ->orderBy($orderBy)
                                     ->all();

        return $this->buildEntities($models);
    }

    /**
     * Создает экземпляр сущности
     *
     * @param CommentView $model
     * @return CommentEntity
     */
    protected function buildEntity(CommentView $model)
    {
        $userEntity = UserRepository::instance()->buildEntity($model->user);

        $parentCommentEntity = ($model->parent) ? CommentRepository::instance()->buildEntity($model->parent) : null;

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
    protected function buildEntities(array $models)
    {
        if(!$models)
        {
            return [];
        }

        $entities = [];

        foreach ($models as $model)
        {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition)
    {
        return Comment::find()->where($condition)->count();
    }
}