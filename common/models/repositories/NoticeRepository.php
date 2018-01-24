<?php

namespace common\models\repositories;

use common\models\activerecords\Notice;
use common\models\entities\NoticeEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

class NoticeRepository implements IRepository
{

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
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return NoticeEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Notice::findOne($condition);

        if(!$model || $model->viewed)
        {
            return null;
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @return NoticeEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $models = Notice::find()->where($condition)->offset($offset)->limit($limit)->orderBy($orderBy)->all();

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function add(NoticeEntity $notice)
    {
        $model = new Notice();

        $this->assignProperties($model, $notice);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save notice with link = ' . $notice->getLink());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function update(NoticeEntity $notice)
    {
        $model = Notice::findOne(['id' => $notice->getId()]);

        if(!$model)
        {
            throw new Exception('Notice with id = ' . $notice->getId() . ' does not exists');
        }

        $this->assignProperties($model, $notice);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update notice with id = ' . $notice->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param NoticeEntity $notice
     * @return NoticeEntity
     * @throws Exception
     */
    public function delete(NoticeEntity $notice)
    {
        $model = Notice::findOne(['id' => $notice->getId()]);

        if(!$model)
        {
            throw new Exception('Notice with id = ' . $notice->getId() . ' does not exists');
        }

        if($model->viewed)
        {
            throw new Exception('Notice with id = ' . $notice->getId() . ' already viewed');
        }

        $model->viewed = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete notice with id = ' . $notice->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Notice $model
     * @param NoticeEntity $notice
     */
    protected function assignProperties(&$model, &$notice)
    {
        $model->recipient_id = $notice->getRecipientId();
        $model->sender_id = $notice->getSenderId();
        $model->content = $notice->getContent();
        $model->link = $notice->getLink();
    }

    /**
     * @param Notice $model
     * @return NoticeEntity
     */
    protected function buildEntity(Notice $model)
    {
        return new NoticeEntity($model->recipient_id,$model->content ,$model->link, $model->sender_id,
                                $model->id, $model->created_at, $model->viewed);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Notice[] $models
     * @return NoticeEntity[]
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
     * @return int|string
     */
    public function getTotalCountByCondition(array $condition)
    {
        return Notice::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


}