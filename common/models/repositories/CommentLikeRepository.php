<?php

namespace common\models\repositories;


use common\models\activerecords\CommentLike;
use common\models\entities\CommentEntity;
use common\models\entities\CommentLikeEntity;
use yii\db\Exception;
use Yii;

class CommentLikeRepository
{
    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return CommentLikeRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return CommentLikeEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = CommentLike::findOne($condition);

        if(!$model)
        {
            throw new Exception('comment_like with ' . json_encode($condition) . ' does not exists');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @return CommentLikeEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null)
    {
        $models = CommentLike::find()->where($condition)->offset($offset)->limit($limit)->all();

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param CommentLikeEntity $commentLike
     * @return CommentLikeEntity
     * @throws Exception
     */
    public function add(CommentLikeEntity $commentLike)
    {
        $model = new CommentLike();

        $this->assignProperties($model, $commentLike);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save comment_like with comment_id = ' . $commentLike->getCommentId() .
                ' and user_id = ' . $commentLike->getUserId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param CommentLikeEntity $commentLike
     * @return CommentLikeEntity
     * @throws Exception
     */
    public function update(CommentLikeEntity $commentLike)
    {
        $model = CommentLike::findOne(['id' => $commentLike->getId()]);

        if(!$model)
        {
            throw new Exception('comment_like with id = ' . $commentLike->getId() . ' does not exists');
        }

        $this->assignProperties($model, $commentLike);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update comment_like with id = ' . $commentLike->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * @param CommentLikeEntity $commentLike
     * @return CommentLikeEntity
     * @throws Exception
     */
    public function delete(CommentLikeEntity $commentLike)
    {
        $model = CommentLike::findOne(['id' => $commentLike->getId()]);

        if(!$model)
        {
            throw new Exception('comment_like with id = ' . $commentLike->getId() . ' does not exists');
        }

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete comment_like with id = ' . $commentLike->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param CommentLike $model
     * @param CommentLikeEntity $commentLike
     */
    protected function assignProperties(&$model, &$commentLike)
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
    protected function buildEntity(CommentLike $model)
    {
        return new CommentLikeEntity($model->comment_id, $model->user_id, $model->liked,$model->id,
            $model->created_at, $model->updated_at);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param CommentLike[] $models
     * @return CommentLikeEntity[]
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


    // #################### UNIQUE METHODS OF CLASS ######################

    /**
     * @param CommentEntity $comment
     * @return int
     */
    public function getAmountLikes(CommentEntity $comment)
    {
        return CommentLike::find()->where(['comment_id' => $comment->getId(), 'liked' => true])->count();
    }

    /**
     * @param CommentEntity $comment
     * @return int
     */
    public function getAmountDislikes(CommentEntity $comment)
    {
        return CommentLike::find()->where(['comment_id' => $comment->getId(), 'liked' => false])->count();
    }
}