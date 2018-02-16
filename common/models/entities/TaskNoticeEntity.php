<?php

namespace common\models\entities;


use common\models\interfaces\IEntity;
use common\models\interfaces\INotice;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\task\TaskRepository;

/**
 * Class TaskNoticeEntity
 * @package common\models\entities
 *
 * @property integer $id
 * @property integer $taskId
 * @property integer $noticeId
 * @property TaskEntity $task
 * @property NoticeEntity $notice
 */
class TaskNoticeEntity implements IEntity, INotice
{
    private $id;
    private $taskId;
    private $noticeId;

    private $task;
    private $notice;

    /**
     * TaskNoticeEntity constructor.
     * @param int $taskId
     * @param int $noticeId
     * @param int|null $id
     */
    public function __construct(int $taskId, int $noticeId, int $id = null)
    {
        $this->id = $id;
        $this->taskId = $taskId;
        $this->noticeId = $noticeId;
    }

    /**
     * @return int
     */
    public function getId() { return $this->id; }

    /**
     * @return int
     */
    public function getTaskId() { return $this->taskId; }

    /**
     * @return int
     */
    public function getNoticeId() { return $this->noticeId; }

    /**
     * @return TaskEntity|IEntity|null
     */
    public function getTask()
    {
        if($this->task === null)
        {
            $this->task = TaskRepository::instance()->findOne(['id' => $this->taskId]);
        }

        return $this->task;
    }

    /**
     * @return NoticeEntity|IEntity|null
     */
    public function getNotice()
    {
        if($this->notice === null)
        {
            $this->notice = NoticeRepository::instance()->findOne(['id' => $this->noticeId]);
        }

        return $this->notice;
    }
}