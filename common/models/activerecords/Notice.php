<?php

namespace common\models\activerecords;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notice".
 *
 * @property integer $id
 * @property integer $recipient_id
 * @property string $content
 * @property integer $created_at
 * @property boolean $viewed
 *
 * @property Users $recipient
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notice';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->viewed = false;
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
            [['recipient_id', 'content'], 'required'],
            [['recipient_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['viewed'], 'boolean'],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipient_id' => 'Recipient ID',
            'content' => 'Content',
            'created_at' => 'Created At',
            'viewed' => 'Viewed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(Users::className(), ['id' => 'recipient_id']);
    }
}
