<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "participant".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 * @property boolean $approved
 * @property integer $approved_at
 * @property boolean $blocked
 *
 * @property Company $company
 * @property Users $profile
 */
class Participant extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%participant}}';
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
            [['user_id'], 'required'],
            [['user_id', 'company_id'], 'integer'],
            [['approved', 'blocked'], 'boolean'],
            [['approved_at', 'blocked_at','created_at', 'updated_at'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public static function findByUsername($username)
    {
        $user =  Users::findOne(['username' => $username]);
        return static::findOne(['user_id' => $user->id, 'company_id' => null]);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->profile->password);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->profile->auth_key;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
