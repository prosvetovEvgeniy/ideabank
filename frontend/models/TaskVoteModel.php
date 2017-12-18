<?php

namespace frontend\models;


use common\models\activerecords\TaskLike;
use yii\base\Model;

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
    public $userId;
    public $taskId;
    public $liked;

    public function rules()
    {
        return [
            [['userId', 'taskId', 'liked'], 'required'],
            [['userId', 'taskId'], 'integer'],
            [['liked'], 'boolean']
        ];
    }

    /**
     * @return bool
     */
    public function recordExist()
    {
        $taskLike = TaskLike::findOne([
            'task_id' => $this->taskId,
            'user_id' => $this->userId,
            'liked'   => $this->liked
        ]);

        if($taskLike !== null)
        {
            return true;
        }

        return false;
    }
}