<?php
namespace common\models\forms;

use common\models\entities\ParticipantEntity;
use common\models\repositories\ParticipantRepository;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package common\models\forms
 *
 * @property string $username
 * @property string $password
 * @property bool   $rememberMe
 *
 * @property ParticipantEntity $user
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'exist','targetClass' => '\common\models\activerecords\Users', 'message' => 'Введен не существующий логин'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомните меня'
        ];
    }

    /**
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        $participantStub = $this->getParticipantStub();

        if (!$participantStub || !$participantStub->validatePassword($this->$attribute))
        {
            $this->addError($attribute, 'Incorrect username or password.');
        }
    }

    /**
     * @return bool
     */
    public function login()
    {
        if (!$this->validate())
        {
            return false;
        }

        return Yii::$app->user->login($this->getParticipantStub(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * Возвращает заглушку из таблицы participant
     *
     * @return ParticipantEntity|null
     */
    protected function getParticipantStub()
    {
        if ($this->user === null)
        {
            $this->user = ParticipantRepository::instance()->findByUserName($this->username);
        }

        return $this->user;
    }
}
