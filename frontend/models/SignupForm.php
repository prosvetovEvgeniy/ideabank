<?php
namespace frontend\models;

use common\models\Company;
use common\models\Participant;
use common\models\Users;
use yii\base\Model;

/**
 * Signup form
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
            [['username'], 'unique', 'targetClass' => '\common\models\Users', 'message' => 'Такой логин уже занят'],
            [['username'], 'string', 'min' => 2, 'max' => 255],

            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => '\common\models\Users', 'message' => 'Такой email уже занят'],

            [['password'], 'string', 'min' => 6],

            [['phone'], 'string'],
            [['phone'], 'validatePhone', 'skipOnEmpty'=> true],

            [['firstName', 'secondName', 'lastName'], 'string', 'length' => [2,30]],

            [['companyName'], 'unique', 'targetClass' => '\common\models\Company', 'targetAttribute' => ['companyName' => 'name'] , 'message' => 'Такая компания уже зарегистрирована']
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
     * Signs user up.
     *
     * @return Participant|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Users();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($this->scenario === self::SCENARIO_USER_SIGNUP)
        {
            if($user->save())
            {
                $participant = new Participant();
                $participant->user_id = $user->id;

                return $participant->save() ? $participant : null;
            }
        }

        else if($this->scenario === self::SCENARIO_DIRECTOR_SIGNUP)
        {
            $user->first_name = $this->firstName;
            $user->second_name = $this->secondName;
            $user->last_name = $this->lastName;
            $user->phone = $this->phone;

            $company = new Company();
            $company->name = $this->companyName;

            if($user->save() && $company->save())
            {

                $participant = new Participant();
                $participant->user_id = $user->id;
                $participant->company_id = $company->id;

                if(!$participant->save())
                {
                    return null;
                }

                $participant = new Participant();
                $participant->user_id = $user->id;

                return $participant->save() ? $participant : null;
            }
        }

        return null;
    }
}
