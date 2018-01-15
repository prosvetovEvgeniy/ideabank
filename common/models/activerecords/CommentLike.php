<?php

namespace common\models\activerecords;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comment_like".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property boolean $liked
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Comment $comment
 * @property Users $user
 */
class CommentLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_like';
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
            [['comment_id', 'user_id'], 'required'],
            [['comment_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['liked'], 'boolean'],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
}
