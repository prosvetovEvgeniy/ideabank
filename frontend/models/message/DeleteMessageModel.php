<?php

namespace frontend\models\message;


use common\models\repositories\MessageRepository;
use yii\base\Model;
use yii\db\Exception;
use common\models\entities\MessageEntity;

/**
 * Class DeleteMessageModel
 * @package frontend\models
 *
 * @property int $selfId
 * @property int $messageId
 * @property int $companionId
 */
class DeleteMessageModel extends Model
{
    const SCENARION_DELETE_MESSAGE = 'deleteMessage';
    const SCENARION_DELETE_DIALOG = 'deleteDialog';

    public $selfId;
    public $messageId;
    public $companionId;

    public function rules()
    {
        return [
            [['selfId', 'messageId'], 'required', 'on' => self::SCENARION_DELETE_MESSAGE],
            [['selfId', 'companionId'], 'required', 'on' => self::SCENARION_DELETE_DIALOG],
            [['selfId', 'messageId', 'companionId'], 'integer'],
        ];
    }

    /**
     * @return bool | MessageEntity
     */
    public function deleteMessage()
    {
        $message = MessageRepository::instance()->findOne([
            'self_id' => $this->selfId,
            'id'      => $this->messageId
        ]);

        if(!$message)
        {
            return false;
        }

        try
        {
            return MessageRepository::instance()->delete($message);
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function deleteDialog()
    {
        return MessageRepository::instance()->deleteAll([
            'self_id'      => $this->selfId,
            'companion_id' => $this->companionId,
            'deleted'      => false
        ]);
    }
}