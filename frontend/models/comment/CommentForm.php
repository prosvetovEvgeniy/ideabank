<?php

namespace frontend\models\comment;


use common\models\activerecords\Comment;
use common\models\entities\CommentEntity;
use common\models\repositories\CommentRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class CommentForm
 * @package frontend\models
 *
 * @property int $parentId
 * @property int $taskId
 * @property string $content
 */
class CommentForm extends Model
{
    public $parentId;
    public $taskId;
    public $content;

    public function rules()
    {
        return [
            [['content', 'taskId'], 'required'],
            [['content'], 'string', 'length' => [1, 2000]],
            [['content'], 'trim'],
            [['parentId', 'taskId'], 'integer'],
            [['parentId'], 'checkOnEntrance'],
            [['parentId'], 'filter', 'filter' => function($value) {
                return ($value !== '') ? (int) $value : null;
            }]
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

    public function attributeLabels()
    {
        return [
            'content'  => 'Текст'
        ];
    }

    public function saveComment()
    {
        $senderId = Yii::$app->user->identity->getUserId();

        $comment = new CommentEntity($this->taskId, $senderId, $this->content, $this->parentId);

        try
        {
            CommentRepository::instance()->add($comment);
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}