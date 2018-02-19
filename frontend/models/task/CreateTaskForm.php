<?php

namespace frontend\models\task;


use common\components\facades\TaskFacade;
use common\components\helpers\FileHelper;
use common\components\helpers\LinkHelper;
use common\components\helpers\NoticeHelper;
use common\components\helpers\ParticipantHelper;
use common\models\activerecords\Project;
use common\models\activerecords\Users;
use common\models\entities\NoticeEntity;
use common\models\entities\TaskEntity;
use common\models\entities\TaskFileEntity;
use common\models\repositories\notice\NoticeRepository;
use common\models\repositories\participant\ParticipantRepository;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\task\TaskFileRepository;
use common\models\repositories\task\TaskRepository;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class CreateTaskForm
 * @package frontend\models\task
 *
 * @property string         $title
 * @property string         $content
 * @property int            $authorId
 * @property int            $projectId
 * @property UploadedFile[] $files
 *
 * @property TaskEntity     $task
 */
class CreateTaskForm extends Model
{
    public $title;
    public $content;
    public $authorId;
    public $projectId;
    public $files;

    //сущность сохраненной задачи
    private $task;

    public function rules()
    {
        return [
            [['title', 'content', 'authorId', 'projectId'], 'required'],
            [['authorId', 'projectId'], 'integer'],
            [['authorId'], 'exist', 'targetClass' => Users::className(), 'targetAttribute' => ['authorId' => 'id']],
            [['projectId'], 'exist', 'targetClass' => Project::className(), 'targetAttribute' => ['projectId' => 'id']],
            [['title'], 'string', 'length' => [
                TaskEntity::TITLE_MIN_LENGTH,
                TaskEntity::TITLE_MAX_LENGTH
            ]],
            [['content'], 'string', 'length' => [TaskEntity::CONTENT_MIN_LENGTH,
                TaskEntity::CONTENT_MAX_LENGTH
            ]],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => TaskFileEntity::MAX_FILES_TO_TASK, 'maxSize' => TaskFileEntity::MAX_SIZE_FILES] //maxSize = 100MB
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'     => 'Заголовок',
            'content'   => 'Содержание',
            'projectId' => 'Проект',
            'files'     => 'Файлы'
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $project = ProjectRepository::instance()->findOne(['id' => $this->projectId]);

        /**
         * если пользователь не является участником проекта по каким то причинам
         */
        if(!ParticipantHelper::instance()->checkOnParticipantInProject($project)) {
            return false;
        }

        $taskFacade = new TaskFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->task = $taskFacade->createTask(
                new TaskEntity(
                    $this->title,
                    $this->content,
                    $this->authorId,
                    $this->projectId,
                    TaskEntity::STATUS_ON_CONSIDERATION,
                    $project->getDefaultVisibilityArea()
                ),
                $this->files
            );

            $transaction->commit();
            return true;
        }
        catch (Exception $e) {
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