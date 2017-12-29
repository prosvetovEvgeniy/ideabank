<?php

namespace common\models\activerecords;

use common\models\activerecords\Company;
use common\models\activerecords\Project;
use common\models\activerecords\Users;
use common\models\entities\UserEntity;
use common\models\repositories\UserRepository;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\IdentityInterface;
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
 *
 * @property Company $company
 * @property Users   $profile
 * @property Project $project
 *
 * @property UserEntity $userEntity
 */
class Participant extends ActiveRecord implements IdentityInterface
{
    const USER_ROLE = 'user';
    const MANAGER_ROLE = 'manager';
    const PROJECT_DIRECTOR_ROLE = 'projectDirector';
    const COMPANY_DIRCTOR_ROLE = 'companyDirector';

    private $userEntity;

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

        $this->blocked = false;
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
            [['approved', 'blocked'], 'boolean'],
            [['approved_at', 'blocked_at','created_at', 'updated_at'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
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
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return UserEntity
     */
    public function getEntity()
    {
        if($this->userEntity === null)
        {
            $this->userEntity = UserRepository::instance()->findOne(['id' => $this->user_id]);
        }

        return$this->userEntity;
    }
}
