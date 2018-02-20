<?php

namespace frontend\models\task;

use common\components\facades\TaskFacade;
use common\models\entities\TaskEntity;
use common\models\repositories\task\TaskRepository;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Class DeleteTaskModel
 * @package frontend\models\task
 *
 * @property int $id
 * @property TaskEntity $task
 */
class DeleteTaskModel extends Model
{
    public $id;
    private $task;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function delete()
    {
        if (!$this->validate()){
            return false;
        }

        $task = TaskRepository::instance()->findOne(['id' => $this->id]);

        $user = Yii::$app->user;

        if (!$task || $task->getDeleted()) {
            return false;
        }

        if ($task->getAuthorId() !== $user->getId() && !$user->isManager($task->getProjectId())){
            return false;
        }

        if ($task->hasChildren() && !$user->isManager($task->getProjectId())){
            return false;
        }

        $taskFacade = new TaskFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->task = $taskFacade->deleteTask($task);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @return TaskEntity
     */
    public function getTask()
    {
        return $this->task;
    }
}