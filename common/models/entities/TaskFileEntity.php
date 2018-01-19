<?php

namespace common\models\entities;

use common\models\repositories\TaskRepository;
use Yii;
use yii\helpers\FileHelper;


/**
 * Class TaskFileEntity
 * @package common\models\entities
 *
 * @property int $id
 * @property int $taskId
 * @property string $hashName
 * @property string $originalName
 * @property int $createdAt
 * @property bool $deleted
 *
 * @property TaskEntity $task
 */
class TaskFileEntity
{
    public const PATH_TO_FILE = 'uploads/tasks/';

    protected $id;
    protected $taskId;
    protected $hashName;
    protected $originalName;
    protected $createdAt;
    protected $deleted;

    //кеш связанных сущностей
    protected $task;


    /**
     * TaskFileEntity constructor.
     * @param int $taskId
     * @param string $hashName
     * @param string $originalName
     * @param int|null $id
     * @param int|null $createdAt
     * @param bool|null $deleted
     * @param TaskEntity|null $task
     */
    public function __construct(int $taskId, string $hashName, string $originalName, int $id = null,
                                int $createdAt = null, bool $deleted = null, TaskEntity $task = null)
    {
        $this->id = $id;
        $this->taskId = $taskId;
        $this->hashName = $hashName;
        $this->originalName = $originalName;
        $this->createdAt = $createdAt;
        $this->deleted = $deleted;

        $this->task = $task;
    }


    // #################### SECTION OF GETTERS ######################


    /**
     * @return int
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getTaskId() { return $this->taskId; }

    /**
     * @return string
     */
    public function getHashName() { return $this->hashName; }

    /**
     * @return string
     */
    public function getOriginalName() { return $this->originalName; }

    /**
     * @return int|null
     */
    public function getCreatedAt() { return $this->createdAt; }

    /**
     * @return bool
     */
    public function getDeleted() { return $this->deleted; }


    // #################### SECTION OF SETTERS ######################


    /**
     * @param int $value
     */
    public function setTaskId(int $value)
    {
        $this->taskId = $value;
    }

    /**
     * @param string $value
     */
    public function setHashName(string $value)
    {
        $this->hashName = $value;
    }

    /**
     * @param string $value
     */
    public function setOriginalName(string $value)
    {
        $this->originalName = $value;
    }

    /**
     * @param int $value
     */
    public function setCreatedAt(int $value)
    {
        $this->createdAt = $value;
    }


    // #################### SECTION OF RELATIONS ######################


    public function getTask()
    {
        if($this->task === null)
        {
            $this->task = TaskRepository::instance()->findOne(['id' => $this->taskId]);
        }

        return $this->task;
    }


    // #################### SECTION OF LOGIC ######################


    /**
     * @return bool|string
     */
    public function getWebAlias()
    {
        return Yii::getAlias('@web/' . self::PATH_TO_FILE . $this->getHashName());
    }

    /**
     * @return bool|string
     */
    public function getWebRootAlias()
    {
        return Yii::getAlias('@webroot/' . self::PATH_TO_FILE . $this->getHashName());
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getMimeType()
    {
        return FileHelper::getMimeType($this->getWebRootAlias());
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isImage()
    {
        $mimeType = $this->getMimeType();

        return (strpos($mimeType, 'image') !== false) ? true : false ;
    }
}