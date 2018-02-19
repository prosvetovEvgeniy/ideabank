<?php

namespace frontend\models\task;

use common\models\activerecords\TaskLike;
use yii\base\Model;
use common\models\repositories\task\TaskLikeRepository;
use common\models\entities\TaskLikeEntity;
use yii\db\Exception;
use Yii;

/**
 * Class TaskVoteModel
 * @package frontend\models
 *
 * @property int  $userId
 * @property int  $taskId
 * @property bool $liked
 */
class TaskVoteModel extends Model
{
    public const SCENARIO_DELETE = 'delete';

    public $userId;
    public $taskId;
    public $liked;

    public function rules()
    {
        return [
            [['userId', 'taskId', 'liked'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['userId', 'taskId'], 'required', 'on' => self::SCENARIO_DELETE],
            [['userId', 'taskId'], 'integer'],
            [['liked'], 'boolean']
        ];
    }

    /**
     * @return bool
     */
    public function add()
    {
        if (!$this->validate() || $this->recordExist()) {
            return false;
        }

        $taskLike = new TaskLikeEntity($this->taskId, $this->userId, $this->liked);

        try {
            TaskLikeRepository::instance()->add($taskLike);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function delete()
    {
        if (!$this->validate()) {
            return false;
        }

        $taskLike = TaskLikeRepository::instance()->findOne([
            'task_id' => $this->taskId,
            'user_id' => Yii::$app->user->identity->getUserId(),
        ]);

        if (!$taskLike) {
            return false;
        }

        try {
            TaskLikeRepository::instance()->delete($taskLike);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function reverse()
    {
        if (!$this->validate()) {
            return false;
        }

        $taskLike = TaskLikeRepository::instance()->findOne([
            'task_id' => $this->taskId,
            'user_id' => Yii::$app->user->identity->getUserId(),
        ]);

        if (!$taskLike) {
            return false;
        }

        ($taskLike->getLiked() === true) ? $taskLike->dislike() : $taskLike->like();

        try {
            TaskLikeRepository::instance()->update($taskLike);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    private function recordExist()
    {
        $taskLike = TaskLike::findOne([
            'task_id' => $this->taskId,
            'user_id' => $this->userId,
            'liked'   => $this->liked
        ]);

        if ($taskLike !== null) {
            return true;
        }

        return false;
    }
}