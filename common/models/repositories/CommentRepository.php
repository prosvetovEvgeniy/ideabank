<?php

namespace common\models\repositories;


use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use yii\db\Exception;
use Yii;

class CommentRepository
{
    /**
     * Возвращает экземпляр класса
     *
     * @return CommentRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return CommentEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = Comment::findOne($condition);

        if(!$model)
        {
            throw new Exception('Comment with ' . json_encode($condition) . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Comment with ' . json_encode($condition) . ' already deleted');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return CommentEntity[]
     * @throws Exception
     */
    public function findAll(array $condition)
    {
        /** @var Comment[] $models */
        $models = Comment::findAll($condition);

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
     * Добавляет сущность в БД
     *
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function add(CommentEntity $comment)
    {
        $model = new Comment();

        $this->assignProperties($model, $comment);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save comment with content = ' . $comment->getContent());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function update(CommentEntity $comment)
    {
        $model = Comment::findOne(['id' => $comment->getId()]);

        if(!$model)
        {
            throw new Exception('Comment with id = ' . $comment->getId() . ' does not exists');
        }

        $this->assignProperties($model, $comment);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update comment with id = ' . $comment->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param CommentEntity $comment
     * @return CommentEntity
     * @throws Exception
     */
    public function delete(CommentEntity $comment)
    {
        $model = Comment::findOne(['id' => $comment->getId()]);

        if(!$model)
        {
            throw new Exception('Comment with id = ' . $comment->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Comment with id = ' . $comment->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete comment with id = ' . $comment->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Comment $model
     * @param CommentEntity $comment
     */
    protected function assignProperties(&$model, &$comment)
    {
        $model->task_id = $comment->getTaskId();
        $model->sender_id = $comment->getSenderId();
        $model->content = $comment->getContent();
        $model->comment_id = $comment->getCommentId();
        $model->private = $comment->getPrivate();
    }

    /**
     * Создает экземпляр сущности
     *
     * @param Comment $model
     * @return CommentEntity
     */
    protected function buildEntity(Comment $model)
    {
        return new CommentEntity($model->task_id, $model->sender_id,$model->content, $model->id,
                                 $model->comment_id, $model->private, $model->created_at, $model->updated_at,
                                 $model->deleted);
    }
}