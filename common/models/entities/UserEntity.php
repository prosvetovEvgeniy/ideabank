<?php

namespace common\models\entities;
use common\models\repositories\CommentLikeRepository;
use common\models\repositories\CommentRepository;
use common\models\repositories\MessageRepository;
use common\models\repositories\NoticeRepository;
use common\models\repositories\ParticipantRepository;
use common\models\repositories\TaskLikeRepository;
use common\models\repositories\TaskRepository;

/**
 * Class UserEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $firstName
 * @property string $secondName
 * @property string $lastName
 * @property string $avatar
 * @property string $authKey
 * @property string $passwordResetToken
 * @property int $createdAt
 * @property int $updatedAt
 * @property bool $deleted
 */
class UserEntity
{
    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $phone;
    protected $firstName;
    protected $secondName;
    protected $lastName;
    protected $avatar;
    protected $authKey;
    protected $passwordResetToken;
    protected $createdAt;
    protected $updatedAt;
    protected $deleted;


    /**
     * UserEntity constructor.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param int|null $id
     * @param string|null $phone
     * @param string|null $firstName
     * @param string|null $secondName
     * @param string|null $lastName
     * @param string|null $avatar
     * @param string|null $authKey
     * @param string|null $passwordResetToken
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     */
    public function __construct(string $username, string $password, string $email, int $id = null,
                                string $phone = null, string $firstName = null, string $secondName = null,
                                string $lastName = null, string $avatar = null, string $authKey = null,
                                string $passwordResetToken = null, int $createdAt = null,
                                int $updatedAt = null, bool $deleted = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->lastName = $lastName;
        $this->avatar = $avatar;
        $this->authKey = $authKey;
        $this->passwordResetToken = $passwordResetToken;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deleted = $deleted;
    }


    // #################### SECTION OF GETTERS ######################

    /**
     * @return int | null
     */
    public function getId() { return $this->id; }

    /**
     * @return string
     */
    public function getUsername() { return $this->username; }

    /**
     * @return string
     */
    public function getPassword() { return $this->password; }

    /**
     * @return string
     */
    public function getEmail() { return $this->email; }

    /**
     * @return string | null
     */
    public function getPhone() { return $this->phone; }

    /**
     * @return string | null
     */
    public function getFirstName() { return $this->firstName; }

    /**
     * @return string | null
     */
    public function getSecondName() { return $this->secondName; }

    /**
     * @return string | null
     */
    public function getLastName() { return $this->lastName; }

    /**
     * @return string | null
     */
    public function getAvatar() { return $this->avatar; }

    /**
     * @return string | null
     */
    public function getAuthKey() { return $this->authKey; }

    /**
     * @return string | null
     */
    public function getPasswordResetToken() { return $this->passwordResetToken; }

    /**
     * @return int | null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return int | null
     */
    public function getUpdatedAt() { return $this->updatedAt; }

    /**
     * @return bool | null
     */
    public function getDeleted() { return $this->deleted; }


    // #################### SECTION OF SETTERS ######################

    /**
     * @param string $value
     */
    public function setUsername(string $value) { $this->username = $value; }

    /**
     * @param string $value
     */
    public function setPassword(string $value) { $this->password = $value; }

    /**
     * @param string $value
     */
    public function setEmail(string $value) { $this->email = $value; }

    /**
     * @param string $value
     */
    public function setPhone(string $value) { $this->phone = $value; }

    /**
     * @param string $value
     */
    public function setFirstName(string $value) { $this->firstName = $value; }

    /**
     * @param string $value
     */
    public function setSecondName(string $value) { $this->secondName = $value; }

    /**
     * @param string $value
     */
    public function setLastName(string $value) { $this->lastName = $value; }

    /**
     * @param string $value
     */
    public function setAvatar(string $value) { $this->avatar = $value; }

    /**
     * @param string $value
     */
    public function setAuthKey(string $value) { $this->authKey = $value; }

    /**
     * @param string $value
     */
    public function setPasswordResetToken(string $value) { $this->passwordResetToken = $value; }


    // #################### SECTION OF RELATIONS ######################

    /**
     * @return ParticipantEntity[]
     */
    public function getParticipants()
    {
        return ParticipantRepository::instance()->findAll(['user_id' => $this->getId()]);
    }

    /**
     * @return CommentLikeEntity[]
     */
    public function getCommentLikes()
    {
        return CommentLikeRepository::instance()->findAll(['user_id' => $this->getId()]);
    }

    /**
     * @return CommentEntity[]
     */
    public function getComments()
    {
        return CommentRepository::instance()->findAll(['sender_id' => $this->getId()]);
    }

    /**
     * @return TaskEntity[]
     */
    public function getTasks()
    {
        return TaskRepository::instance()->findAll(['author_id' => $this->getId()]);
    }

    /**
     * @return TaskLikeEntity[]
     */
    public function getTaskLikes()
    {
        return TaskLikeRepository::instance()->findAll(['user_id' => $this->getId()]);
    }

    /**
     * @return NoticeEntity[]
     */
    public function getNotices()
    {
        return NoticeRepository::instance()->findAll(['recipient_id' => $this->getId()]);
    }

    /**
     * @return MessageEntity[]
     */
    public function getMessages()
    {
        return MessageRepository::instance()->findAll(['self_id' => $this->getId()]);
    }


    // #################### SECTION OF LOGIC ######################
}































