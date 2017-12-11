<?php

namespace common\models\activerecords;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $self_id
 * @property integer $companion_id
 * @property string $content
 * @property boolean $is_sender
 * @property integer $created_at
 * @property boolean $deleted
 *
 * @property Users $self
 * @property Users $companion
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->deleted = false;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
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
            [['self_id', 'companion_id', 'is_sender', 'content'], 'required'],
            [['self_id', 'companion_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['is_sender', 'deleted'], 'boolean'],
            [['self_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['self_id' => 'id']],
            [['companion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['companion_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'self_id' => 'Self ID',
            'companion_id' => 'Companion ID',
            'content' => 'Content',
            'is_sender' => 'Is Sender',
            'created_at' => 'Created At',
            'deleted' => 'Deleted',
        ];
    }
}
