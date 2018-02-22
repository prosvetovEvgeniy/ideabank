<?php

namespace common\models\entities;

use common\models\interfaces\IEntity;
use common\models\repositories\comment\CommentLikeRepository;
use common\models\repositories\comment\CommentViewRepository;
use common\models\repositories\message\MessageRepository;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\task\TaskLikeRepository;
use common\models\repositories\task\TaskRepository;
use common\models\repositories\user\UserRepository;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\Html;
use yii\web\IdentityInterface;
use yii\base\Exception;

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
 *
 * @property ParticipantEntity   $participants
 * @property CommentLikeEntity[] $commentLikes
 * @property CommentEntity       $comments
 * @property TaskEntity[]        $tasks
 * @property TaskLikeEntity[]    $taskLikes
 * @property NoticeEntity[]      $notices
 * @property MessageEntity[]     $messages
 */
class UserEntity implements IEntity, IdentityInterface
{
    public const USERNAME_MAX_LENGTH = 50;
    public const EMAIL_MAX_LENGTH = 50;
    public const NAME_MAX_LENGTH = 50;
    public const PASSWORD_MIN_LENGTH = 6;

    public const PATH_TO_AVATAR = 'uploads/avatars/';
    private const PATH_TO_AVATAR_STUB = 'images/stub-img.png';

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

    //кеш связанных сущностей
    protected $participants;
    protected $commentLikes;
    protected $comments;
    protected $tasks;
    protected $taskLikes;
    protected $notices;
    protected $messages;


    /**
     * UserEntity constructor.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string|null $phone
     * @param string|null $firstName
     * @param string|null $secondName
     * @param string|null $lastName
     * @param string|null $avatar
     * @param string|null $authKey
     * @param string|null $passwordResetToken
     * @param int|null $id
     * @param int|null $createdAt
     * @param int|null $updatedAt
     * @param bool|null $deleted
     * @param array|null $participants
     */
    public function __construct(
        string $username,
        string $password,
        string $email,
        string $phone = null,
        string $firstName = null,
        string $secondName = null,
        string $lastName = null,
        string $avatar = null,
        string $authKey = null,
        string $passwordResetToken = null,
        int $id = null, int $createdAt = null,
        int $updatedAt = null,
        bool $deleted = null,
        array $participants = null
    ) {
        $this->id = $id;
        $this->username = $username;

        //если сущность уже была создана, то пароль генерировать не нужно
        if ($id){
            $this->password = $password;
        } else {
            try {
                $this->password = Yii::$app->security->generatePasswordHash($password);
            } catch (Exception $exception) {

            }
        }

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
        $this->deleted = $deleted ?? false;

        $this->participants = $participants;
    }


    // ######## SECTION OF REALIZATION IDENTITY ############


    public function getUser(){
        return $this;
    }

    /**
     * @return int | null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @param int|string $id
     * @return UserEntity|null|IdentityInterface
     */
    public static function findIdentity($id)
    {
        return UserRepository::instance()->findOne(['id' => $id]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @return null|string
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    // #################### SECTION OF GETTERS ######################

    /**
     * @param bool $encode
     * @return string
     */
    public function getUsername(bool $encode = false) 
    {
        return ($encode) ? Html::encode($this->username) : $this->username;
    }

    /**
     * @param bool $encode
     * @return string
     */
    public function getPassword(bool $encode = false) 
    {
        return ($encode) ? Html::encode($this->password) : $this->password;
    }

    /**
     * @param bool $encode
     * @return string
     */
    public function getEmail(bool $encode = false) 
    {
        return ($encode) ? Html::encode($this->email) : $this->email;
    }

    /**
     * @param bool $encode
     * @return string | null
     */
    public function getPhone(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->phone) : $this->phone;
    }

    /**
     * @param bool $encode
     * @return string | null
     */
    public function getFirstName(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->firstName) : $this->firstName;
    }

    /**
     * @param bool $encode
     * @return string | null
     */
    public function getSecondName(bool $encode = false) 
    {
        return ($encode) ? Html::encode($this->secondName) : $this->secondName;
    }

    /**
     * @param bool $encode
     * @return string | null
     */
    public function getLastName(bool $encode = false)
    {
        return ($encode) ? Html::encode($this->lastName) : $this->lastName;
    }

    /**
     * @param bool $encode
     * @return string | null
     */
    public function getAvatar(bool $encode = false) 
    {
        return ($encode) ? Html::encode($this->avatar) : $this->avatar;
    }

    /**
     * @param bool $encode
     * @return string | null
     */
    public function getPasswordResetToken(bool $encode = false) 
    {
        return ($encode) ? Html::encode($this->passwordResetToken) : $this->passwordResetToken;
    }

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
     * @throws \yii\base\Exception
     */
    public function setPassword(string $value)
    {
        $this->password = Yii::$app->security->generatePasswordHash($value);
    }

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
        if ($this->participants === null) {
            $this->participants = ParticipantRepository::instance()->findAll(['user_id' => $this->getId()]);
        }

        return $this->participants;
    }

    /**
     * @return CommentLikeEntity[]
     */
    public function getCommentLikes()
    {
        if ($this->commentLikes === null) {
            $this->commentLikes = CommentLikeRepository::instance()->findAll(['user_id' => $this->getId()]);
        }

        return $this->commentLikes;
    }

    /**
     * @return CommentEntity[]
     */
    public function getComments()
    {
        if ($this->comments === null) {
            $this->comments = CommentViewRepository::instance()->findAll(['sender_id' => $this->getId()]);
        }

        return $this->comments;
    }

    /**
     * @return TaskEntity[]
     */
    public function getTasks()
    {
        if ($this->tasks === null) {
            $this->tasks = TaskRepository::instance()->findAll(['author_id' => $this->getId()]);
        }

        return $this->tasks;
    }

    /**
     * @return TaskLikeEntity[]
     */
    public function getTaskLikes()
    {
        if ($this->taskLikes === null) {
            $this->taskLikes = TaskLikeRepository::instance()->findAll(['user_id' => $this->getId()]);
        }

        return $this->taskLikes;
    }

    /**
     * @return NoticeEntity[]
     */
    public function getNotices()
    {
        if ($this->notices === null) {
            $this->notices = NoticeRepository::instance()->findAll(['recipient_id' => $this->getId()]);
        }

        return $this->notices;
    }

    /**
     * @return MessageEntity[]
     */
    public function getMessages()
    {
        if ($this->messages === null) {
            $this->messages = MessageRepository::instance()->findAll(['self_id' => $this->getId()]);
        }

        return $this->messages;
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * Возвращает путь к аватару,
     * не путать с getAvatar(), который
     * возвращает только название аватара
     *
     * @return bool|string
     */
    public function getAvatarAlias()
    {
        if (!$this->avatar) {
            return Yii::getAlias('@web/' . self::PATH_TO_AVATAR_STUB);
        }

        return Yii::getAlias('@web/' . self::PATH_TO_AVATAR . $this->avatar);
    }

    /**
     * @param bool $encode
     * @return string
     */
    public function getFio(bool $encode = false)
    {
        $fio = $this->getSecondName() . ' ' . $this->getFirstName() . ' ' . $this->getLastName();
        
        return ($encode) ? Html::encode($fio) : $fio;
    }
}
