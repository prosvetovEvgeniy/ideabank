<?php

namespace frontend\models\task;


use common\components\helpers\FileHelper;
use common\components\helpers\NoticeHelper;
use common\models\activerecords\Project;
use common\models\activerecords\Users;
use common\models\entities\NoticeEntity;
use common\models\entities\TaskEntity;
use common\models\entities\TaskFileEntity;
use common\models\repositories\NoticeRepository;
use common\models\repositories\ProjectRepository;
use common\models\repositories\TaskFileRepository;
use common\models\repositories\TaskRepository;
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
 * @property int            $status
 * @property int            $visibilityArea
 * @property UploadedFile[] $files
 *
 * @property TaskEntity $task
 * @property NoticeHelper $noticeHelper;
 */
class CreateTaskForm extends Model
{
    public $title;
    public $content;
    public $authorId;
    public $projectId;
    public $status;
    public $visibilityArea;
    public $files;

    //сущность сохраненной задачи
    private $task;

    private $noticeHelper;

    public function rules()
    {
        return [
            [['title', 'content', 'authorId', 'projectId'], 'required'],
            [['authorId', 'projectId'], 'integer'],
            [['authorId'], 'exist', 'targetClass' => Users::className(), 'targetAttribute' => ['authorId' => 'id']],
            [['projectId'], 'exist', 'targetClass' => Project::className(), 'targetAttribute' => ['projectId' => 'id']],
            [['title'], 'string', 'length' => [4, 100]],
            [['content'], 'string', 'length' => [4, 13000]],
            [['status'], 'in', 'range' => [
                TaskEntity::STATUS_ON_CONSIDERATION,
                TaskEntity::STATUS_IN_PROGRESS,
                TaskEntity::STATUS_COMPLETED,
                TaskEntity::STATUS_MERGED
            ]],
            [['visibilityArea'], 'in', 'range' => [
                TaskEntity::VISIBILITY_AREA_ALL,
                TaskEntity::VISIBILITY_AREA_REGISTERED,
                TaskEntity::VISIBILITY_AREA_PRIVATE
            ]],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10, 'maxSize' => 100 * (1000000)] //maxSize = 100MB
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
        if(!$this->validate())
        {
            return false;
        }

        $project = ProjectRepository::instance()->findOne(['id' => $this->projectId]);

        $task = new TaskEntity(
            $this->title,
            $this->content,
            $this->authorId,
            $this->projectId,
            TaskEntity::STATUS_ON_CONSIDERATION,
            $project->getDefaultVisibilityArea()
        );

        $this->noticeHelper = new NoticeHelper($this->content);

        $this->beginTransaction();

        try
        {
            $this->task = TaskRepository::instance()->add($task);

            if(!$this->saveFiles() || !$this->saveNotices())
            {
                $this->rollBack();

                return false;
            }

            $this->commit();

            return true;
        }
        catch (Exception $e)
        {
            $this->rollBack();

            return false;
        }
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    private function saveFiles()
    {
        try
        {
            if($this->files)
            {
                foreach ($this->files as $file)
                {
                    $fileHelper = new FileHelper($file->extension, TaskFileRepository::instance());

                    $hashName = $fileHelper->getHash('hash_name');

                    $taskFile = new TaskFileEntity($this->task->getId(), $hashName, $file->name);
                    TaskFileRepository::instance()->add($taskFile);

                    //если файл не сохранился, откатываем транзакцию
                    if(!$file->saveAs(TaskFileEntity::PATH_TO_FILE . $hashName))
                    {
                        return false;
                    }
                }
            }

            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * @return bool
     */
    private function saveNotices()
    {
        try
        {
            //если есть упоминания, то сохраняем их тоже
            if($this->noticeHelper->hasNotice())
            {
                foreach ($this->noticeHelper->getNoticedUsers() as $noticedUser)
                {
                    NoticeRepository::instance()->add(
                        new NoticeEntity(
                            $noticedUser->getId(),
                            $this->content,
                            $this->getLink(),
                            $this->authorId
                        )
                    );
                }
            }

            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    private function beginTransaction()
    {
        if($this->files || $this->noticeHelper->hasNotice()) { Yii::$app->db->beginTransaction(); }
    }

    private function commit()
    {
        if($this->files || $this->noticeHelper->hasNotice()) { Yii::$app->db->transaction->commit(); }
    }

    private function rollBack()
    {
        if($this->files || $this->noticeHelper->hasNotice()) { Yii::$app->db->transaction->rollBack(); }
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
            'taskId' => $this->task->getId()
        ]);
    }
}