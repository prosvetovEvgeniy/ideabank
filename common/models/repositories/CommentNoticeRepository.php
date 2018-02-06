<?php

namespace common\models\repositories;


use common\models\activerecords\CommentNotice;
use common\models\builders\CommentNoticeBuilder;
use common\models\entities\CommentNoticeEntity;
use common\models\interfaces\IEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

class CommentNoticeRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new CommentNoticeBuilder();
    }

    /**
     * @return CommentNoticeRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @return CommentNoticeEntity|IEntity|null
     */
    public function findOne(array $condition)
    {
        $model = CommentNotice::findOne($condition);

        if(!$model)
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
     * @return CommentNoticeEntity[]|IEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = CommentNotice::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) CommentNotice::find()->where($condition)->count();
    }

    /**
     * @param CommentNoticeEntity $commentNotice
     * @return CommentNoticeEntity
     * @throws Exception
     */
    public function add(CommentNoticeEntity $commentNotice)
    {
        $model = new CommentNotice();

        $this->builderBehavior->assignProperties($model, $commentNotice);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save comment_notice with id = ' . $commentNotice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param CommentNoticeEntity $commentNotice
     * @return CommentNoticeEntity
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(CommentNoticeEntity $commentNotice)
    {
        $model = CommentNotice::findOne(['id' => $commentNotice->getId()]);

        if(!$model)
        {
            throw new Exception('CommentNotice with id = ' . $commentNotice->getId() . ' does not exists');
        }

        if(!$model->delete())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete comment_notice with id = ' . $commentNotice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }
}