<?php

namespace common\models\repositories;

use common\models\activerecords\Notice;
use common\models\entities\NoticeEntity;
use yii\db\Exception;
use Yii;

class NoticeRepository
{
    /**
     * Возвращает экземпляр класса
     *
     * @return NoticeRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return NoticeEntity
     * @throws Exception
     */
    public function findOne(array $condition)
    {
        $model = Notice::findOne($condition);

        if(!$model)
        {
            throw new Exception('Notice with ' . json_encode($condition) . ' does not exists');
        }

        if($model->viewed)
        {
            throw new Exception('Notice with ' . json_encode($condition) . ' already viewed');
        }

        return $this->buildEntity($model);
    }

    /**
     * Возвращает сущности по условию
     *
     * @param array $condition
     * @return NoticeEntity[]
     * @throws Exception
     */
    public function findAll(array $condition)
    {
        /** @var Notice[] $models */
        $models = Notice::findAll($condition);

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
            throw new Exception('Cannot save notice with content = ' . $notice->getContent());
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
        $model->content = $notice->getContent();
    }

    /**
     * @param Notice $model
     * @return NoticeEntity
     */
    protected function buildEntity(Notice $model)
    {
        return new NoticeEntity($model->recipient_id, $model->content, $model->id, $model->created_at,
                                $model->viewed);
    }
}