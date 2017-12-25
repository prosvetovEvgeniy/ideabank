<?php

namespace frontend\models\message;

use common\models\entities\MessageEntity;
use common\models\repositories\MessageRepository;
use yii\base\Model;
use yii\db\Exception;
use Yii;

/**
 * Class SendMessageForm
 * @package frontend\models
 *
 * @property int $selfId
 * @property string $content
 * @property int $companionId
 * @property MessageEntity $messageToSelf
 */
class SendMessageForm extends Model
{
    public $selfId;
    public $content;
    public $companionId;
    protected $messageToSelf;

    public function rules()
    {
        return [
          [['selfId', 'content', 'companionId'], 'required'],
          [['selfId', 'companionId'], 'integer'],
          [['content'], 'string', 'length' => [1, 1000]],
        ];
    }

    public function checkOnEntrance($attribute, $params)
    {
        $parentComment = Comment::findOne(['id' => $this->$attribute, 'task_id' => $this->taskId]);

        if(!$parentComment)
        {
            $this->addError($attribute);
        }
    }

    public function save()
    {
        $messageToSelf = new MessageEntity(
            $this->selfId,
            $this->companionId,
            $this->content,
            true
        );

        $messageToCompanion = new MessageEntity(
            $this->companionId,
            $this->selfId,
            $this->content,
            false
        );

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $this->messageToSelf = MessageRepository::instance()->add($messageToSelf);

            MessageRepository::instance()->add($messageToCompanion);

            $transaction->commit();

            return true;
        }
        catch (Exception $e)
        {
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * @return MessageEntity
     */
    public function getSelfMessage() { return $this->messageToSelf; }

}