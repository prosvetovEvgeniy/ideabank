<?php

namespace common\models\activerecords;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class AuthLog
 * @package common\models\activerecords
 *
 * @property int    $id
 * @property int    $changer_id
 * @property int    $changeable_id
 * @property string $new_role_name
 * @property int    $created_at
 *
 * @property Participant $changer
 * @property Participant $changeable
 */
class AuthLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_log';
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
            [['changeable_id', 'new_role_name'], 'required'],
            [['changer_id', 'changeable_id', 'created_at'], 'integer'],
            [['new_role_name'], 'string']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChanger()
    {
        return $this->hasOne(Participant::className(), ['id' => 'changer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangeable()
    {
        return $this->hasOne(Participant::className(), ['id' => 'changeable_id']);
    }
}