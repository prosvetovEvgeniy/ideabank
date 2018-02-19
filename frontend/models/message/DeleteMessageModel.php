<?php

namespace frontend\models\message;

use common\models\repositories\message\MessageRepository;
use yii\base\Model;
use yii\db\Exception;

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
    const SCENARIO_DELETE_MESSAGE = 'DELETE_MESSAGE';
    const SCENARIO_DELETE_DIALOG = 'DELETE_DIALOG';

    public $selfId;
    public $messageId;
    public $companionId;

    public function rules()
    {
        return [
            [['selfId', 'messageId'], 'required', 'on' => self::SCENARIO_DELETE_MESSAGE],
            [['selfId', 'companionId'], 'required', 'on' => self::SCENARIO_DELETE_DIALOG],
            [['selfId', 'messageId', 'companionId'], 'integer'],
        ];
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $message = MessageRepository::instance()->findOne([
            'self_id' => $this->selfId,
            'id'      => $this->messageId
        ]);

        if (!$message) {
            return false;
        }

        try {
            MessageRepository::instance()->delete($message);
            return true;
        } catch (Exception $e) {
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