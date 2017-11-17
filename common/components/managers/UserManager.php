<?php

namespace common\components\managers;

use common\models\Users;
use yii\db\Exception;

class UserManager
{
    /**
     * @return Users
     * @throws Exception
     */
    public function createUser($username, $email, $password, $phone = null, $firstName = null, $secondName = null, $lastName = null, $avatar = null)
    {
        $user = new Users();
        $user->username = $username;
        $user->email = $email;
        $user->phone = $phone;
        $user->first_name = $firstName;
        $user->second_name = $secondName;
        $user->last_name = $lastName;
        $user->avatar = $avatar;
        $user->setPassword($password);
        $user->generateAuthKey();

        if(!$user->save())
        {
            throw new Exception('Cannot save user to the database');
        }

        return $user;
    }
}