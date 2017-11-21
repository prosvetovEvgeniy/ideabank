<?php

namespace common\models\activerecords;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $sender_id
 * @property string $content
 * @property integer $comment_id
 * @property boolean $private
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $deleted
 *
 * @property Comment $comment
 * @property Comment[] $comments
 * @property Task $task
 * @property Users $sender
 * @property CommentLike[] $commentLikes
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'sender_id', 'content'], 'required'],
            [['task_id', 'sender_id', 'comment_id', 'created_at', 'updated_at'], 'integer'],
            [['private', 'deleted'], 'boolean'],
            [['content'], 'string', 'max' => 2000],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'sender_id' => 'Sender ID',
            'content' => 'Content',
            'comment_id' => 'Comment ID',
            'private' => 'Private',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['comment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Users::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentLikes()
    {
        return $this->hasMany(CommentLike::className(), ['comment_id' => 'id']);
    }
}
