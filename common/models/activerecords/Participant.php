<?php

namespace common\models\activerecords;

use common\models\entities\UserEntity;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "participant".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 * @property integer $project_id
 * @property boolean $approved
 * @property integer $approved_at
 * @property boolean $blocked
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property boolean $deleted
 *
 * @property Company        $company
 * @property Users          $user
 * @property Project        $project
 * @property AuthAssignment $authAssignment
 * @property AuthLog        $authLog
 *
 * @property UserEntity $userEntity
 */
class Participant extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%participant}}';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
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
            [['user_id', 'company_id', 'project_id'], 'integer'],
            [['approved', 'blocked', 'deleted'], 'boolean'],
            [['approved_at', 'blocked_at','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
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
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getAuthLog()
    {
        return $this->hasMany(AuthLog::className(), ['changeable_id' => 'id']);
    }
}
