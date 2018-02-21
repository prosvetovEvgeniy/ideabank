<?php

namespace common\models\builders;

use common\models\activerecords\Message;
use common\models\entities\MessageEntity;

class MessageEntityBuilder
{
    /**
     * @return MessageEntityBuilder
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Message $model
     * @param MessageEntity $message
     */
    public function assignProperties(Message &$model, MessageEntity &$message)
    {
        $model->self_id = $message->getSelfId();
        $model->companion_id = $message->getCompanionId();
        $model->is_sender = $message->getIsSender();
        $model->content = $message->getContent();
        $model->viewed = $message->getViewed();
        $model->deleted = $message->getDeleted();
    }

    /**
     * @param Message $model
     * @return MessageEntity
     */
    public function buildEntity(Message $model)
    {
        $self = UserEntityBuilder::instance()->buildEntity($model->self);
        $companion = UserEntityBuilder::instance()->buildEntity($model->companion);

        return new MessageEntity(
            $model->self_id, 
            $model->companion_id, 
            $model->content, 
            $model->is_sender,
            $model->id, 
            $model->viewed, 
            $model->created_at, 
            $model->deleted, $self,
            $companion
        );
    }

    /**
     * @param array $models
     * @return MessageEntity[]
     */
    public function buildEntities(array $models)
    {
        if (!$models) {
            return [];
        }

        $entities = [];

        foreach ($models as $model) {
            $entities[] = $this->buildEntity($model);
        }

        return $entities;
    }
}