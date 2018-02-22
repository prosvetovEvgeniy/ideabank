<?php

namespace common\models\repositories\message;

use common\models\activerecords\Message;
use common\models\builders\MessageEntityBuilder;
use common\models\entities\MessageEntity;
use common\models\entities\UserEntity;
use common\models\interfaces\IRepository;
use yii\db\Exception;
use Yii;

/**
 * Class MessageRepository
 * @package common\models\repositories
 *
 * @property MessageEntityBuilder $builderBehavior
 */
class MessageRepository implements IRepository
{
    public $builderBehavior;

    public function __construct()
    {
        $this->builderBehavior = new MessageEntityBuilder();
    }

    // #################### STANDARD METHODS ######################

    /**
     * Возвращает экземпляр класса
     *
     * @return MessageRepository
     */
    public static function instance(): IRepository
    {
        return new self();
    }

    /**
     * @param array $condition
     * @return MessageEntity|null
     */
    public function findOne(array $condition)
    {
        $model = Message::findOne($condition);

        if (!$model || $model->deleted) {
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
     * @return MessageEntity[]|\common\models\interfaces\IEntity[]
     */
    public function findAll(
        array $condition,
        int $limit = 20,
        int $offset = null,
        string $orderBy = null,
        array $with = []
    ) {
        $models = Message::find()->where($condition)
                                 ->with($with)
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($orderBy)
                                 ->all();

        return $this->builderBehavior->buildEntities($models);
    }

    /**
     * @param MessageEntity $message
     * @return MessageEntity
     * @throws Exception
     */
    public function add(MessageEntity $message)
    {
        $model = new Message();

        $this->builderBehavior->assignProperties($model, $message);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot save message with content = ' . $message->getContent());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param MessageEntity $message
     * @return MessageEntity
     * @throws Exception
     */
    public function update(MessageEntity $message)
    {
        $model = Message::findOne(['id' => $message->getId()]);

        if (!$model) {
            throw new Exception('Message with id = ' . $message->getId() . ' does not exists');
        }

        $this->builderBehavior->assignProperties($model, $message);

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot update message with id = ' . $message->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param MessageEntity $message
     * @return MessageEntity
     * @throws Exception
     */
    public function delete(MessageEntity $message)
    {
        $model = Message::findOne(['id' => $message->getId()]);

        if (!$model) {
            throw new Exception('Message with id = ' . $message->getId() . ' does not exists');
        }

        if ($model->deleted) {
            throw new Exception('Message with id = ' . $message->getId() . ' already deleted');
        }

        $model->deleted = true;

        if (!$model->save()) {
            Yii::error($model->errors);
            throw new Exception('Cannot delete message with id = ' . $message->getId());
        }

        return $this->builderBehavior->buildEntity($model);
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getTotalCountByCondition(array $condition): int
    {
        return (int) Message::find()->where($condition)->count();
    }


    // #################### UNIQUE METHODS OF CLASS ######################


    /**
     * @param array $conditionForSet
     * @param array $conditionForSearch
     * @return bool
     */
    public function updateAll(array $conditionForSet, array $conditionForSearch)
    {
        $amountUpdatedRows = Message::updateAll($conditionForSet, $conditionForSearch);

        return ($amountUpdatedRows === 0) ? false : true;
    }

    /**
     * @param array $condition
     * @return bool
     */
    public function deleteAll(array $condition)
    {
        return $this->updateAll(['deleted' => true], $condition);
    }

    /**
     * @param array $condition
     * @return bool
     */
    public function viewAll(array $condition)
    {
        return $this->updateAll(['viewed' => true], $condition);
    }

    /**
     * @return int
     */
    public function getAllUnViewedMsgCount()
    {
        return $this->getTotalCountByCondition([
            'is_sender' => false,
            'viewed'    => false,
            'deleted'   => false,
            'self_id'   => Yii::$app->user->getId()
        ]);
    }

    public function getUnViewedMsgCount(UserEntity $self, UserEntity $companion)
    {
        return $this->getTotalCountByCondition([
            'is_sender'    => false,
            'viewed'       => false,
            'deleted'      => false,
            'self_id'      => $self->getId(),
            'companion_id' => $companion->getId()
        ]);
    }
}