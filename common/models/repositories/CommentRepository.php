<?php

namespace common\models\repositories;


use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use common\models\entities\TaskEntity;
use yii\db\Exception;
use Yii;

class CommentRepository
{

    // #################### STANDARD METHODS ######################

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
     * @return CommentEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Comment::findOne($condition);

        if(!$model || $model->deleted)
        {
            return null;
        }

        return $this->buildEntity($model);
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
        $model->parent_id = $comment->getParentId();
        $model->private = $comment->getPrivate();
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
     * @param array $condition
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition)
    {
        return Comment::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * Рассчитывает количество комментриев до текущего
     *
     * @param CommentEntity $comment
     * @return int|string
     */
    public function getCountRecordsBeforeComment(CommentEntity $comment)
    {
        return $this->getTotalCountByCondition([
            'and',
            ['task_id' => $comment->getTaskId()],
            ['<', 'id', $comment->getId()],
            ['<', 'created_at', $comment->getCreatedAt()]
        ]);
    }
}