<?php

namespace common\models\repositories;


use common\models\activerecords\Comment;
use common\models\builders\CommentEntityBuilder;
use common\models\entities\CommentEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class CommentRepository
 * @package common\models\repositories
 *
 * @property CommentEntityBuilder $builderBehavior
 */
class CommentRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new CommentEntityBuilder();
    }


    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return CommentRepository
     */
    public static function instance(): IRepository
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

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return CommentEntity|\common\models\interfaces\IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Comment::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
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

        $this->builderBehavior->assignProperties($model, $comment);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save comment with content = ' . $comment->getContent());
        }

        return $this->builderBehavior->buildEntity($model);
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

        $this->builderBehavior->assignProperties($model, $comment);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update comment with id = ' . $comment->getId());
        }

        return $this->builderBehavior->buildEntity($model);
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

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Comment::find()->where($condition)->count();
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