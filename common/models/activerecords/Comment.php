<?php

namespace common\models\activerecords;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $sender_id
 * @property string  $content
 * @property integer $parent_id
 * @property boolean $private
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $deleted
 *
 * @property Comment $comment
 * @property Comment[] $comments
 * @property Task $task
 * @property Users $user
 * @property Comment $parent
 * @property CommentLike[] $commentLikes
 *
 */
class Comment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'sender_id', 'content'], 'required'],
            [['task_id', 'sender_id', 'parent_id', 'created_at', 'updated_at'], 'integer'],
            [['private', 'deleted'], 'boolean'],
            [['content'], 'string', 'max' => 2000],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'sender_id']);
    }

    public function getCommentLikes()
    {
        return $this->hasMany(CommentLike::className(), ['comment_id' => 'id']);
    }
}
