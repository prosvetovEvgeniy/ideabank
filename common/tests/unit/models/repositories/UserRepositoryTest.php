<?php

namespace common\tests\models\repositories;


use common\models\entities\UserEntity;
use common\models\repositories\UserRepository;


class UserRepositoryTest extends BaseRepositoryTest
{
    /** @var array */
    protected $data = [
        'username'           => 'username',
        'password'           => 'password',
        'email'              => 'email',
        'phone'              => 'phone',
        'firstName'          => 'firstName',
        'secondName'         => 'secondName',
        'lastName'           => 'lastName',
        'avatar'             => 'avatar',
        'authKey'            => 'v',
        'passwordResetToken' => 'passwordResetToken',
    ];

    /** @var array */
    protected $dataForSetters = [
        'username'           => 'new username',
        'password'           => 'new password',
        'email'              => 'new email',
        'phone'              => 'new phone',
        'firstName'          => 'new firstName',
        'secondName'         => 'new secondName',
        'lastName'           => 'new lastName',
        'avatar'             => 'new avatar',
        'authKey'            => 'new authKey',
        'passwordResetToken' => 'new passwordResetToken'
    ];

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInstance()
    {
        $userRepository = UserRepository::instance();

        $this->assertEquals($userRepository, new UserRepository());
    }

    public function testAdd()
    {
        /** @var UserEntity $user */
        $user = UserRepository::instance()->add(
            new UserEntity(
                $this->data['username'],
                $this->data['password'],
                $this->data['email'],
                $this->data['phone'],
                $this->data['firstName'],
                $this->data['secondName'],
                $this->data['lastName'],
                $this->data['avatar'],
                $this->data['authKey'],
                $this->data['passwordResetToken']
            )
        );

        $this->tester->seeRecord($this->paths['users'], ['id' => $user->getId()]);
    }

    public function testUpdate()
    {
        /** @var UserEntity $user */
        $user = UserRepository::instance()->add(
            new UserEntity(
                $this->data['username'],
                $this->data['password'],
                $this->data['email'],
                $this->data['phone'],
                $this->data['firstName'],
                $this->data['secondName'],
                $this->data['lastName'],
                $this->data['avatar'],
                $this->data['authKey'],
                $this->data['passwordResetToken']
            )
        );

        $user->setUsername($this->dataForSetters['username']);
        $user->setPassword($this->dataForSetters['password']);
        $user->setPasswordResetToken($this->dataForSetters['passwordResetToken']);
        $user->setAuthKey($this->dataForSetters['authKey']);
        $user->setAvatar($this->dataForSetters['avatar']);
        $user->setFirstName($this->dataForSetters['firstName']);
        $user->setSecondName($this->dataForSetters['secondName']);
        $user->setLastName($this->dataForSetters['lastName']);
        $user->setPhone($this->dataForSetters['phone']);

        $this->assertEquals(UserRepository::instance()->update($user), $user);
    }

    public function testDelete()
    {
        /** @var UserEntity $user */
        $user = UserRepository::instance()->add(
            new UserEntity(
                $this->data['username'],
                $this->data['password'],
                $this->data['email'],
                $this->data['phone'],
                $this->data['firstName'],
                $this->data['secondName'],
                $this->data['lastName'],
                $this->data['avatar'],
                $this->data['authKey'],
                $this->data['passwordResetToken']
            )
        );

        UserRepository::instance()->delete($user);

        $this->tester->seeRecord($this->paths['users'], ['id' => $user->getId(), 'deleted' => true]);
    }
}