<?php

namespace frontend\models\task;


use common\components\helpers\FileHelper;
use common\components\helpers\NoticeHelper;
use common\models\entities\NoticeEntity;
use common\models\entities\TaskEntity;
use common\models\entities\UserEntity;
use common\models\repositories\NoticeRepository;
use common\models\repositories\TaskFileRepository;
use common\models\repositories\TaskRepository;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use common\models\entities\TaskFileEntity;
use Yii;
use common\models\activerecords\Project;
use common\models\repositories\ProjectRepository;

/**
 * Class EditTaskForm
 * @package frontend\models\task
 *
 * @property string         $title;
 * @property string         $content
 * @property int            $projectId
 * @property UploadedFile[] $files
 *
 * @property TaskEntity     $task
 * @property NoticeHelper   $noticeHelper
 */
class EditTaskForm extends Model
{
    public $title;
    public $content;
    public $projectId;
    public $files;

    //сущность изменяемой задачи
    private $task;

    private $noticeHelper;

    /**
     * EditTaskForm constructor.
     * @param TaskEntity $task
     * @param array $config
     */
    public function __construct(TaskEntity $task, array $config = [])
    {
        parent::__construct($config);

        $this->title = $task->getTitle();
        $this->content = $task->getContent();
        $this->projectId = $task->getProjectId();

        $this->task = $task;
    }

    public function rules()
    {
        return [
            [['title', 'content', 'projectId'], 'required'],
            [['projectId'], 'integer'],
            [['projectId'], 'exist', 'targetClass' => Project::className(), 'targetAttribute' => ['projectId' => 'id']],
            [['title'], 'string', 'length' => [
                TaskEntity::TITLE_MIN_LENGTH,
                TaskEntity::TITLE_MAX_LENGTH
            ]],
            [['content'], 'string', 'length' => [
                TaskEntity::CONTENT_MIN_LENGTH,
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
            'files'     => 'Добавить файлы'
        ];
    }

    public function save()
    {
        if(!$this->validate())
        {
            return false;
        }

        $this->task->setTitle($this->title);
        $this->task->setContent($this->content);
        $this->task->setProjectId($this->projectId);

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            TaskRepository::instance()->update($this->task);
            TaskFileRepository::instance()->saveFiles($this->files, $this->task);
            NoticeRepository::instance()->saveNoticesForTask($this->task, $this->getLink());

            $transaction->commit();

            return true;
        }
        catch (Exception $e)
        {
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * Возвращает проекты для определенного пользователя
     *
     * @return array
     */
    public function getProjects()
    {
        /**
         * @var UserEntity $user
         */
        $user = Yii::$app->user->identity->getUser();

        $projects = ProjectRepository::instance()->getProjectsForUser($user);

        return ArrayHelper::map($projects,
                                function($project){ return $project->getId(); },
                                function($project) { return $project->getName();});
    }

    /**
     * Возращает ссылку по которой будет находится созданная задача.
     *
     * @return string
     */
    public function getLink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            '/task/view',
            'id' => $this->task->getId()
        ]);
    }
}