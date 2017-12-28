<?php

namespace frontend\models\authentication;

use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Signup form
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $firstName
 * @property string $secondName
 * @property string $lastName
 * @property string $companyName
 */
class SignupForm extends Model
{
    const SCENARIO_USER_SIGNUP = 'user signup';
    const SCENARIO_DIRECTOR_SIGNUP = 'director signup';

    public $username;
    public $email;
    public $password;
    public $phone;
    public $firstName;
    public $secondName;
    public $lastName;
    public $companyName;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required', 'on' => self::SCENARIO_USER_SIGNUP],
            [['username', 'email', 'password', 'phone', 'firstName', 'secondName', 'lastName', 'companyName'], 'required', 'on' => self::SCENARIO_DIRECTOR_SIGNUP],

            [['username'], 'trim'],
            [['username'], 'unique', 'targetClass' => '\common\models\activerecords\Users', 'message' => 'Такой логин уже занят'],
            [['username'], 'string', 'min' => 2, 'max' => 255],

            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => '\common\models\activerecords\Users', 'message' => 'Такой email уже занят'],

            [['password'], 'string', 'min' => 6],

            [['phone'], 'string'],
            [['phone'], 'validatePhone', 'skipOnEmpty'=> true],

            [['firstName', 'secondName', 'lastName'], 'string', 'length' => [2,30]],

            [['companyName'], 'unique', 'targetClass' => '\common\models\activerecords\Company', 'targetAttribute' => ['companyName' => 'name'] , 'message' => 'Такая компания уже зарегистрирована']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'phone' => 'Номер телефона',
            'firstName' => 'Имя',
            'secondName' => 'Фамилия',
            'lastName' => 'Отчество',
            'companyName' => 'Название компании'
        ];
    }

    public function validatePhone()
    {
        if((strlen($this->phone)<10))
        {
            $errorMsg= 'Введите корректный номер телефона';
            $this->addError('phone',$errorMsg);
        }
    }

    /**
     * @return null
     * @throws Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        if($this->scenario === self::SCENARIO_USER_SIGNUP)
        {
            $transaction = Yii::$app->db->beginTransaction();

            try
            {
                $user = Yii::createObject(UserManager::class)->createUser($this->username, $this->email, $this->password);
                $participant = Yii::createObject(ParticipantManager::class)->attachUser($user);

                $transaction->commit();

                return $participant;
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                throw $e;
            }

        }
        else if($this->scenario === self::SCENARIO_DIRECTOR_SIGNUP)
        {
            $transaction = Yii::$app->db->beginTransaction();

            try
            {
                $user = Yii::createObject(UserManager::class)->createUser(
                    $this->username, $this->email, $this->password, $this->phone,
                    $this->firstName, $this->secondName, $this->lastName
                );

                $company = Yii::createObject(CompanyManager::class)->createCompany($this->companyName);
                $participant = Yii::createObject(ParticipantManager::class)->attachDirector($user, $company);

                $transaction->commit();

                return $participant;
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                throw $e;
            }
        }

        return null;
    }
}
