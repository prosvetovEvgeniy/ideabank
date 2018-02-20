<?php

namespace frontend\models\profile;

use common\components\helpers\FileHelper;
use common\models\entities\UserEntity;
use common\models\repositories\user\UserRepository;
use yii\base\Model;
use yii\db\Exception;
use Yii;
use yii\web\UploadedFile;

/**
 * Class ChangeOwnData
 * @package frontend\models\profile
 *
 * @property string $username
 * @property string $email
 * @property string $firstName
 * @property string $secondName
 * @property string $lastName
 * @property string $phone
 * @property UploadedFile $avatar
 * @property UserEntity $user
 */
class ChangeOwnDataForm extends Model
{
    public $username;
    public $email;
    public $firstName;
    public $secondName;
    public $lastName;
    public $phone;
    public $avatar;

    private $user;


    public function __construct(array $config = [])
    {
        parent::__construct($config);

        //устанавливаем значения полей по умолчанию
        $user = $this->getUser();

        $this->username = $user->getUsername();
        $this->email = $user->getEmail();
        $this->firstName = $user->getFirstName();
        $this->secondName = $user->getSecondName();
        $this->lastName = $user->getLastName();
        $this->phone = $user->getPhone();
    }

    public function rules()
    {
        return [
            [['username'], 'trim'],
            [['username'], 'string', 'min' => 2, 'max' => 50],
            [['username'], 'validateUserName'],

            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 50],
            [['email'], 'validateEmail'],

            [['firstName', 'secondName', 'lastName'], 'string', 'length' => [2,30]],

            [['phone'], 'string'],
            [['phone'], 'validatePhone', 'skipOnEmpty'=> true],

            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 10 * (1000000)] //maxSize = 10MB
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'   => 'Логин',
            'email'      => 'Email',
            'phone'      => 'Номер телефона',
            'firstName'  => 'Имя',
            'secondName' => 'Фамилия',
            'lastName'   => 'Отчество',
            'avatar'     => 'Аватар'
        ];
    }

    /**
     * @param $attribute
     */
    public function validateUsername($attribute)
    {
        $recordExists = UserRepository::instance()->findOne([
            'and',
            ['username' => $this->$attribute],
            ['not', ['id' => Yii::$app->user->getId()]]
        ]);

        if ($recordExists) {
            $this->addError('username', 'Данный логин уже занят');
        }
    }

    /**
     * @param $attribute
     */
    public function validateEmail($attribute)
    {
        $recordExists = UserRepository::instance()->findOne([
            'and',
            ['email' => $this->$attribute],
            ['not', ['id' => Yii::$app->user->getId()]]
        ]);

        if ($recordExists) {
            $this->addError('email', 'Данный email уже занят');
        }
    }

    /**
     * @param $attribute
     */
    public function validatePhone($attribute)
    {
        $phoneLength = strlen($this->$attribute);

        if ($phoneLength < 5 || $phoneLength > 12) {
            $errorMsg= 'Номер телефона не корректный';
            $this->addError('phone', $errorMsg);
        }
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        try
        {
            $user = $this->getUser();

            $user->setUsername($this->username);
            $user->setEmail($this->email);
            $user->setFirstName($this->firstName);
            $user->setSecondName($this->secondName);
            $user->setLastName($this->lastName);
            $user->setPhone($this->phone);

            if ($this->avatar) {

                $fileHelper = new FileHelper($this->avatar->extension, UserRepository::instance());
                $hashName = $fileHelper->getHash('avatar');
                $user->setAvatar($hashName);

                if (!$this->avatar->saveAs(UserEntity::PATH_TO_AVATAR . $hashName)) {
                    return false;
                }
            }

            UserRepository::instance()->update($user);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return UserEntity|null
     */
    private function getUser()
    {
        if ($this->user === null) {
            $this->user = UserRepository::instance()->findOne(['id' => Yii::$app->user->getId()]);
        }

        return $this->user;
    }
}