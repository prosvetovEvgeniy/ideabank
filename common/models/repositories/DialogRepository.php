<?php

namespace common\models\repositories;


use common\models\entities\MessageEntity;
use common\models\activerecords\Message;
use common\models\entities\UserEntity;
use yii\helpers\ArrayHelper;


class DialogRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return DialogRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает массив сущностей по условию
     *
     * @param array $condition
     * @param int $limit
     * @param int|null $offset
     * @param string $orderBy
     * @return MessageEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null, string $orderBy = null)
    {
        $dialogIds = Message::find()->select('MAX(id) as id')
                                    ->where($condition )
                                    ->offset($offset)
                                    ->limit($limit)
                                    ->groupBy('companion_id')
                                    ->all();

        $models = Message::find()->with('companion')
                                 ->where(['in', 'id', $dialogIds])
                                 ->orderBy($orderBy)
                                 ->all();

        return $this->buildEntities($models);
    }

    /**
     * Присваивает свойства сущности к модели
     *
     * @param Message $model
     * @param MessageEntity $message
     */
    protected function assignProperties(&$model, &$message)
    {
        $model->self_id = $message->getSelfId();
        $model->companion_id = $message->getCompanionId();
        $model->is_sender = $message->getIsSender();
        $model->content = $message->getContent();
        $model->viewed = $message->getViewed();
    }

    /**
     * @param Message $model
     * @return MessageEntity
     */
    protected function buildEntity(Message $model)
    {
        $self = UserRepository::instance()->buildEntity($model->self);
        $companion = UserRepository::instance()->buildEntity($model->companion);

        return new MessageEntity($model->self_id, $model->companion_id, $model->content, $model->is_sender,
                                 $model->id, $model->viewed, $model->created_at, $model->deleted, $self,
                                 $companion);
    }

    /**
     * Создает экземпляры сущностей
     *
     * @param Message[] $models
     * @return MessageEntity[]
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
        return Message::find()->select('MAX(id) as id')
                              ->where($condition )
                              ->groupBy('companion_id')
                              ->count();
    }
}