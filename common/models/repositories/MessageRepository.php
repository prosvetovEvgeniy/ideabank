<?php

namespace common\models\repositories;


use common\models\activerecords\Message;
use common\models\entities\CompanyEntity;
use common\models\entities\MessageEntity;
use yii\db\Exception;
use Yii;

class MessageRepository
{

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return MessageRepository
     */
    public static function instance()
    {
        return new self();
    }

    /**
     * Возвращает сущность по условию
     *
     * @param array $condition
     * @return MessageEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Message::findOne($condition);

        if(!$model || $model->deleted)
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
     * @return MessageEntity[]
     */
    public function findAll(array $condition, int $limit = 20, int $offset = null)
    {
        $models = Message::find()->where($condition)->offset($offset)->limit($limit)->all();

        return $this->buildEntities($models);
    }

    /**
     * Добавляет сущность в БД
     *
     * @param MessageEntity $message
     * @return MessageEntity
     * @throws Exception
     */
    public function add(MessageEntity $message)
    {
        $model = new Message();

        $this->assignProperties($model, $message);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot save message with content = ' . $message->getContent());
        }

        return $this->buildEntity($model);
    }

    /**
     * Обновляет сущность в БД
     *
     * @param MessageEntity $message
     * @return MessageEntity
     * @throws Exception
     */
    public function update(MessageEntity $message)
    {
        $model = Message::findOne(['id' => $message->getId()]);

        if(!$model)
        {
            throw new Exception('Message with id = ' . $message->getId() . ' does not exists');
        }

        $this->assignProperties($model, $message);

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot update message with id = ' . $message->getId());
        }

        return $this->buildEntity($model);
    }

    /**
     * Помечает сущность как удаленную в БД
     *
     * @param MessageEntity $message
     * @return MessageEntity
     * @throws Exception
     */
    public function delete(MessageEntity $message)
    {
        $model = Message::findOne(['id' => $message->getId()]);

        if(!$model)
        {
            throw new Exception('Message with id = ' . $message->getId() . ' does not exists');
        }

        if($model->deleted)
        {
            throw new Exception('Message with id = ' . $message->getId() . ' already deleted');
        }

        $model->deleted = true;

        if(!$model->save())
        {
            Yii::error($model->errors);
            throw new Exception('Cannot delete message with id = ' . $message->getId());
        }

        return $this->buildEntity($model);
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
    }

    /**
     * @param Message $model
     * @return MessageEntity
     */
    protected function buildEntity(Message $model)
    {
        return new MessageEntity($model->self_id, $model->companion_id, $model->is_sender, $model->content,
                                 $model->id, $model->created_at, $model->deleted);
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

    // #################### UNIQUE METHODS OF CLASS ######################


}