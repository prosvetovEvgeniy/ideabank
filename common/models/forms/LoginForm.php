<?php
namespace common\models\forms;

use common\models\entities\UserEntity;
use common\models\repositories\user\UserRepository;
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
 * @property UserEntity $user
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
        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Не правильное имя пользователя или пароль.');
        }
    }

    /**
     * @return bool
     */
    public function login()
    {
        if (!$this->validate()) {
            return false;
        }

        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * @return UserEntity|null
     */
    private function getUser()
    {
        if ($this->user === null) {
            $this->user = UserRepository::instance()->findOne([
                'username' => $this->username
            ]);
        }

        return $this->user;
    }
}