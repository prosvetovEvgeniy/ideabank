<?php

namespace frontend\models\message;

use common\models\entities\MessageEntity;
use yii\base\Model;

/**
 * Class MessageViewModel
 * @package frontend\models
 *
 * @property MessageEntity $selfMessage
 */
class MessageViewModel extends Model
{
    protected $selfMessage;

    public function __construct(MessageEntity $selfMessage, array $config = [])
    {
        parent::__construct($config);

        $this->selfMessage = $selfMessage;
    }

    public function fields()
    {
        return [
            'content',
            'creationDate',
            'avatar'
        ];
    }

    /**
     * @return string
     */
    public function getContent() { return $this->selfMessage->getContent(); }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreationDate() { return $this->selfMessage->getCreationDate(); }

    /**
     * @return null|string
     */
    public function getAvatar() { return $this->selfMessage->getSelf()->getAvatar(); }
}