<?php
namespace common\models\activerecords;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $avatar
 * @property string $auth_key
 * @property string $password_reset_token
 * @property integer created_at
 * @property integer updated_at
 * @property boolean deleted
 */
class Users extends ActiveRecord
{
    const STATUS_ACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
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
}
