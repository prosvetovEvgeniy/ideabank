<?php

namespace frontend\models\authentication;

use common\models\entities\CompanyEntity;
use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;
use common\models\repositories\company\CompanyRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\user\UserRepository;
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
 *
 * @property ParticipantEntity $participant
 */
class SignupForm extends Model
{
    const SCENARIO_USER_SIGNUP = 'user signup';
    const SCENARIO_DIRECTOR_SIGNUP = 'director sign up';

    public $username;
    public $email;
    public $password;
    public $phone;
    public $firstName;
    public $secondName;
    public $lastName;
    public $companyName;

    //сущность юзера
    protected $participant;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required', 'on' => self::SCENARIO_USER_SIGNUP],
            [['username', 'email', 'password', 'phone', 'firstName', 'secondName', 'lastName', 'companyName'], 'required', 'on' => self::SCENARIO_DIRECTOR_SIGNUP],

            [['username'], 'trim'],
            [['username'], 'unique', 'targetClass' => '\common\models\activerecords\Users', 'message' => 'Такой логин уже занят'],
            [['username'], 'string', 'length' => [2, UserEntity::USERNAME_MAX_LENGTH]],

            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => UserEntity::EMAIL_MAX_LENGTH],
            [['email'], 'unique', 'targetClass' => '\common\models\activerecords\Users', 'message' => 'Такой email уже занят'],

            [['password'], 'string', 'min' => UserEntity::PASSWORD_MIN_LENGTH],

            [['phone'], 'string'],

            [['firstName', 'secondName', 'lastName'], 'string', 'length' => [2, UserEntity::NAME_MAX_LENGTH]],

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

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function signUpUser()
    {
        if (!$this->validate() || $this->scenario !== self::SCENARIO_USER_SIGNUP)
        {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $user = UserRepository::instance()->add(
                new UserEntity($this->username, $this->password, $this->email)
            );

            $this->participant = ParticipantRepository::instance()->add(
                new ParticipantEntity($user->getId())
            );

            $transaction->commit();

            return true;
        }
        catch (Exception $e)
        {
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function signUpDirector()
    {
        if (!$this->validate() || $this->scenario !== self::SCENARIO_DIRECTOR_SIGNUP)
        {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $user = UserRepository::instance()->add(
                new UserEntity(
                    $this->username, $this->password, $this->email,
                    $this->phone, $this->firstName, $this->secondName,
                    $this->lastName
                )
            );

            $company = CompanyRepository::instance()->add(
                new CompanyEntity($this->companyName)
            );

            $participant = new ParticipantEntity(
                $user->getId(), $company->getId()
            );

            $participant->setApproved(true);
            $participant->setApprovedAt(time());

            $this->participant = ParticipantRepository::instance()->add($participant);

            ParticipantRepository::instance()->add(
                new ParticipantEntity($user->getId())
            );

            $transaction->commit();

            return true;
        }
        catch (Exception $e)
        {
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * @return ParticipantEntity
     */
    public function getParticipant() { return $this->participant; }
}
