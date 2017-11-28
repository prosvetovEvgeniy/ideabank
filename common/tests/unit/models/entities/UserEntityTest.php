<?php
namespace common\tests\models\entities;


use common\models\entities\CommentEntity;
use common\models\entities\CommentLikeEntity;
use common\models\entities\MessageEntity;
use common\models\entities\NoticeEntity;
use common\models\entities\ParticipantEntity;
use common\models\entities\TaskEntity;
use common\models\entities\TaskLikeEntity;
use common\models\entities\UserEntity;
use common\models\repositories\UserRepository;

class UserEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var UserEntity */
    protected $user;

    /** @var array */
    protected $data = [
        'id'                 => 1,
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
        'createdAt'          => 1511431761,
        'updatedAt'          => 1511431761,
        'deleted'            => false
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
        $this->user = new UserEntity(
            $this->data['username'],
            $this->data['password'],
            $this->data['email'],
            $this->data['phone'],
            $this->data['firstName'],
            $this->data['secondName'],
            $this->data['lastName'],
            $this->data['avatar'],
            $this->data['authKey'],
            $this->data['passwordResetToken'],
            $this->data['id'],
            $this->data['createdAt'],
            $this->data['updatedAt'],
            $this->data['deleted']
        );
    }

    protected function _after()
    {
    }


    // #################### TESTS OF GETTERS ######################

    public function testGetId()
    {
        $this->assertEquals($this->user->getId(), $this->data['id']);
    }

    public function testGetUsername()
    {
        $this->assertEquals($this->user->getUsername(), $this->data['username']);
    }

    public function testGetPassword()
    {
        $this->assertEquals($this->user->getPassword(), $this->data['password']);
    }

    public function testGetEmail()
    {
        $this->assertEquals($this->user->getEmail(), $this->data['email']);
    }

    public function testGetPhone()
    {
        $this->assertEquals($this->user->getPhone(), $this->data['phone']);
    }

    public function testGetFirstName()
    {
        $this->assertEquals($this->user->getFirstName(), $this->data['firstName']);
    }

    public function testGetSecondName()
    {
        $this->assertEquals($this->user->getSecondName(), $this->data['secondName']);
    }

    public function testGetLastName()
    {
        $this->assertEquals($this->user->getLastName(), $this->data['lastName']);
    }

    public function testGetAvatar()
    {
        $this->assertEquals($this->user->getAvatar(), $this->data['avatar']);
    }

    public function testGetAuthKey()
    {
        $this->assertEquals($this->user->getAuthKey(), $this->data['authKey']);
    }

    public function testGetPasswordResetToken()
    {
        $this->assertEquals($this->user->getPasswordResetToken(), $this->data['passwordResetToken']);
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals($this->user->getCreatedAt(), $this->data['createdAt']);
    }

    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->user->getUpdatedAt(), $this->data['updatedAt']);
    }

    public function testGetDeleted()
    {
        $this->assertEquals($this->user->getDeleted(), $this->data['deleted']);
    }


    // #################### TESTS OF SETTERS ######################

    public function testSetUsername()
    {
        $this->user->setUsername($this->dataForSetters['username']);
        
        $this->assertEquals($this->user->getUsername(), $this->dataForSetters['username']);
    }

    public function testSetPassword()
    {
        $this->user->setPassword($this->dataForSetters['password']);

        $this->assertEquals($this->user->getPassword(), $this->dataForSetters['password']);
    }

    public function testSetEmail()
    {
        $this->user->setEmail($this->dataForSetters['email']);

        $this->assertEquals($this->user->getEmail(), $this->dataForSetters['email']);
    }

    public function testSetPhone()
    {
        $this->user->setPhone($this->dataForSetters['phone']);

        $this->assertEquals($this->user->getPhone(), $this->dataForSetters['phone']);
    }

    public function testSetFirstName()
    {
        $this->user->setFirstName($this->dataForSetters['firstName']);

        $this->assertEquals($this->user->getFirstName(), $this->dataForSetters['firstName']);
    }

    public function testSetSecondName()
    {
        $this->user->setSecondName($this->dataForSetters['secondName']);

        $this->assertEquals($this->user->getSecondName(), $this->dataForSetters['secondName']);
    }

    public function testSetLastName()
    {
        $this->user->setLastName($this->dataForSetters['lastName']);

        $this->assertEquals($this->user->getLastName(), $this->dataForSetters['lastName']);
    }

    public function testSetAvatar()
    {
        $this->user->setAvatar($this->dataForSetters['avatar']);

        $this->assertEquals($this->user->getAvatar(), $this->dataForSetters['avatar']);
    }

    public function testSetAuthKey()
    {
        $this->user->setAuthKey($this->dataForSetters['authKey']);

        $this->assertEquals($this->user->getAuthKey(), $this->dataForSetters['authKey']);
    }

    public function testSetPasswordResetToken()
    {
        $this->user->setPasswordResetToken($this->dataForSetters['passwordResetToken']);

        $this->assertEquals($this->user->getPasswordResetToken(), $this->dataForSetters['passwordResetToken']);
    }


    // #################### TESTS OF RELATIONS ######################

    public function testGetParticipantsCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getParticipants()), true);
    }

    public function testGetParticipantsCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getParticipants()[0]), ParticipantEntity::class);
    }

    public function testGetCommentLikesCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getCommentLikes()), true);
    }

    public function testGetCommentLikesCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getCommentLikes()[0]), CommentLikeEntity::class);
    }

    public function testGetCommentsCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getComments()), true);
    }

    public function testGetCommentsCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getComments()[0]), CommentEntity::class);
    }

    public function testGetTasksCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getTasks()), true);
    }

    public function testGetTasksCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getTasks()[0]), TaskEntity::class);
    }

    public function testGetTaskLikesCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getTaskLikes()), true);
    }

    public function testGetTaskLikesCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getTaskLikes()[0]), TaskLikeEntity::class);
    }

    public function testGetNoticesCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getNotices()), true);
    }

    public function testGetNoticesCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getNotices()[0]), NoticeEntity::class);
    }

    public function testGetMessagesCheckOnArray()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(is_array($user->getMessages()), true);
    }

    public function testGetMessagesCheckOnClassName()
    {
        $user = UserRepository::instance()->findOne(['id' => 1]);

        $this->assertEquals(get_class($user->getMessages()[0]), MessageEntity::class);
    }

    // #################### TESTS OF LOGIC ######################
}