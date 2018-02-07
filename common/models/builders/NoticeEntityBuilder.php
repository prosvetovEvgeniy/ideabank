<?php

namespace common\models\builders;


use common\models\activerecords\Notice;
use common\models\entities\NoticeEntity;

class NoticeEntityBuilder
{
    /**
     * @return NoticeEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Notice $model
     * @param NoticeEntity $notice
     */
    public function assignProperties(&$model, &$notice)
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
    public function buildEntity(Notice $model)
    {
        $sender = UserEntityBuilder::instance()->buildEntity($model->sender);

        return new NoticeEntity($model->recipient_id,$model->content ,$model->link, $model->sender_id,
                                $model->id, $model->created_at, $sender);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Notice[] $models
     * @return NoticeEntity[]
     */
    public function buildEntities(array $models)
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
}