<?php

namespace frontend\models\taskfile;

use common\models\repositories\task\TaskFileRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;


/**
 * Class TaskFileDeleteModel
 * @package frontend\models\taskfile
 *
 * @property int $id
 * @property int $taskId
 */
class TaskFileDeleteModel extends Model
{
    public $id;
    public $taskId;

    public function rules()
    {
        return [
            [['id', 'taskId'], 'required'],
            [['id', 'taskId'], 'integer'],
        ];
    }

    public function delete()
    {
        if(!$this->validate()) {
            return false;
        }

        $taskFile = TaskFileRepository::instance()->findOne([
            'id' => $this->id,
            'task_id' => $this->taskId
        ]);

        if(!$taskFile) {
            return false;
        }

        if($taskFile->getTask()->getAuthorId() !== Yii::$app->user->identity->getUser()->getId() &&
           !Yii::$app->user->isManager($taskFile->getTask()->getProjectId()))
        {
            return false;
        }

        try {
            TaskFileRepository::instance()->delete($taskFile);

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
}