<?php

namespace frontend\models\profile;

use common\models\entities\UserEntity;
use common\models\repositories\user\UserRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class ChangePasswordForm
 * @package frontend\models\profile
 *
 * @property string $oldPassword
 * @property string $newPassword
 * @property string $confirmNewPassword
 * 
 * @property UserEntity $user
 */
class ChangePasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;
    
    private $user;

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'confirmNewPassword'], 'required'],
            [['oldPassword', 'newPassword', 'confirmNewPassword'], 'string', 'length' => [6, 32]],
            [['oldPassword'], 'validateOldPassword'],
            [['newPassword'], 'validateNewPassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'        => 'Старый пароль',
            'newPassword'        => 'Новый пароль',
            'confirmNewPassword' => 'Подтвердите пароль'
        ];
    }

    /**
     * @param $attribute
     */
    public function validateOldPassword($attribute)
    {
        $user = $this->getUser();

        if (!Yii::$app->security->validatePassword($this->$attribute, $user->getPassword())) {
            $this->addError('oldPassword', 'Старый пароль введен не правильно');
        }
    }

    /**
     * @param $attribute
     */
    public function validateNewPassword($attribute)
    {
        if ($this->$attribute !== $this->confirmNewPassword) {
            $this->addError('newPassword', 'Пароли не совпадают');
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

        try {
            $user = $this->getUser();
            $user->setPassword($this->newPassword);

            UserRepository::instance()->update($user);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return UserEntity|null
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = UserRepository::instance()->findOne(['id' => Yii::$app->user->getId()]);
        }
        
        return $this->user;
    }
}