<?php

namespace common\models\repositories\notice;

use common\models\activerecords\Notice;
use common\models\builders\NoticeEntityBuilder;
use common\models\entities\NoticeEntity;
use common\models\interfaces\INotice;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class NoticeRepository
 * @package common\models\repositories
 *
 * @property NoticeEntityBuilder $builderBehavior
 */
class NoticeRepository implements IRepository
{
    private $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new NoticeEntityBuilder();
    }


    // #################### STANDARD METHODS ######################


    /**
     * Возвращает экземпляр класса
     *
     * @return NoticeRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @return NoticeEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Notice::findOne($condition);

        if (!$model) {
            return null;
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @param array $with
     * @return NoticeEntity[]|\common\models\interfaces\IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    ) {
        $models = Notice::find()->where($condition)
                                ->with($with)
                                ->offset($offset)
                                ->limit($limit)
                                ->orderBy($orderBy)
                                ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function add(NoticeEntity $notice)
    {
        $model = new Notice();

        $this->builderBehavior->assignProperties($model, $notice);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save notice with link = ' . $notice->getLink());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function update(NoticeEntity $notice)
    {
        $model = Notice::findOne(['id' => $notice->getId()]);

        if (!$model) {
            throw new Exception('Notice with id = ' . $notice->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $notice);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot update notice with id = ' . $notice->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param INotice $notice
     * @return NoticeEntity
     * @throws Exception
     * @throws \Throwable
     */
    public function delete(INotice $notice)
    {
        $model = Notice::findOne(['id' => $notice->getNoticeId()]);

        if (!$model) {
            throw new Exception('Notice with id = ' . $notice->getNoticeId() . ' does not exists');
        }

        if (!$model->delete()) {
            Yii::error($model->errors);
            throw new Exception('Cannot delete notice with id = ' . $notice->getNoticeId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Notice::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * @param INotice[] $notices
     */
    public function deleteAll(array $notices)
    {
        $noticeIds = ArrayHelper::getColumn($notices, function(INotice $notice){
           return $notice->getNoticeId();
        });

        Notice::deleteAll(['in', 'id', $noticeIds]);
    }
}