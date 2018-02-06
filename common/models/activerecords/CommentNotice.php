<?php

namespace common\models\activerecords;


use yii\db\ActiveRecord;

/**
 * Class CommentNotice
 * @package common\models\activerecords
 *
 * @property integer $id
 * @property integer $comment_id;
 * @property integer $notice_id
 * @property Comment $comment
 * @property Notice  $notice
 */
class CommentNotice extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%comment_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'notice_id'], 'required'],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
            [['notice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notice::className(), 'targetAttribute' => ['notice_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Task::className(), ['id' => 'comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotice()
    {
        return $this->hasOne(Notice::className(), ['id' => 'notice_id']);
    }
}