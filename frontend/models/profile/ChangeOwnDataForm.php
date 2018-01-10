<?php

namespace frontend\models\profile;


use common\models\entities\UserEntity;
use common\models\repositories\UserRepository;
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


    public function __construct(array $config = [])
    {
        parent::__construct($config);

        //устанавливаем значения полей по умолчанию
        $this->fillFields();
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
    public function validateUsername($attribute, $params)
    {
        $user = $this->getUser();

        $recordExists = UserRepository::instance()->findOne([
            'and',
            ['username' => $this->$attribute],
            ['not', ['id' => $user->getId()]]
        ]);

        if($recordExists)
        {
            $this->addError('username', 'Данный логин уже занят');
        }
    }

    /**
     * @param $attribute
     */
    public function validateEmail($attribute)
    {
        $user = $this->getUser();

        $recordExists = UserRepository::instance()->findOne([
            'and',
            ['email' => $this->$attribute],
            ['not', ['id' => $user->getId()]]
        ]);

        if($recordExists)
        {
            $this->addError('email', 'Данный email уже занят');
        }
    }

    /**
     * @param $attribute
     */
    public function validatePhone($attribute)
    {
        $phoneLength = strlen($this->$attribute);

        if($phoneLength < 10 || $phoneLength > 12)
        {
            $errorMsg= 'Номер телефона не корректный';
            $this->addError('phone', $errorMsg);
        }
    }

    public function update()
    {
        if(!$this->validate())
        {
            return false;
        }

        try
        {
            /**
             * @var UserEntity $user
             */
            $user = $this->getUser();

            $user->setUsername($this->username);
            $user->setEmail($this->email);
            $user->setFirstName($this->firstName);
            $user->setSecondName($this->secondName);
            $user->setLastName($this->lastName);
            $user->setPhone($this->phone);

            if($this->avatar)
            {
                $hashName = $this->generateFileHashName($this->avatar->extension);

                $user->setAvatar($hashName);

                if(!$this->avatar->saveAs(UserEntity::PATH_TO_AVATAR . $hashName))
                {
                    return false;
                }
            }

            UserRepository::instance()->update($user);

            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * Заполняет поля формы данными
     */
    public function fillFields()
    {
        /**
         * @var UserEntity $user
         */
        $user = $this->getUser();

        if($user)
        {
            $this->username = $user->getUsername();
            $this->email = $user->getEmail();
            $this->firstName = $user->getFirstName();
            $this->secondName = $user->getSecondName();
            $this->lastName = $user->getLastName();
            $this->phone = $user->getPhone();
        }
    }

    /**
     * Возвращает уникальное имя для сохраняемого файла,
     * если такое имя уже есть, то происходит рекурсивный вызов
     *
     * @param string $fileExtension
     * @param int $nameLength
     * @return mixed|string
     */
    public function generateFileHashName(string $fileExtension, int $nameLength = 16)
    {
        $hashName = Yii::$app->security->generateRandomString($nameLength) . '.' . $fileExtension;

        $file = UserRepository::instance()->findOne(['avatar' => $hashName]);

        return (!$file) ? $hashName : $this->generateFileHashName($fileExtension) ;
    }

    /**
     * @return UserEntity
     */
    private function getUser()
    {
        return Yii::$app->user->identity->getUser();
    }
}