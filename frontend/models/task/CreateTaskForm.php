<?php

namespace frontend\models\task;


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
     * @throws Exception
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

        $noticedUsers = NoticeHelper::getNoticedUsers($this->content);

        //если есть файлы или заметки открываем транзакцию
        if($this->files || $noticedUsers) { Yii::$app->db->beginTransaction(); }

        try
        {
            //сохраняем задачу
            $this->task = TaskRepository::instance()->add($task);

            //если есть файлы, то сохраняем их тоже
            if($this->files)
            {
                foreach ($this->files as $file)
                {
                    $hashName = $this->generateFileHashName($file->extension);
                    $taskFile = new TaskFileEntity($this->task->getId(), $hashName, $file->name);

                    TaskFileRepository::instance()->add($taskFile);

                    //если файл не сохранился, откатываем транзакцию
                    if(!$file->saveAs(TaskFileEntity::PATH_TO_FILE . $hashName))
                    {
                        Yii::$app->db->transaction->rollBack();

                        return false;
                    }
                }
            }

            //если есть упоминания, то сохраняем их тоже
            if($noticedUsers)
            {
                foreach ($noticedUsers as $noticedUser)
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

            if($this->files || $noticedUsers) { Yii::$app->db->transaction->commit(); }

            return true;
        }
        catch (Exception $e)
        {
            if($this->files || $noticedUsers) { Yii::$app->db->transaction->rollBack(); }

            return false;
        }
    }

    /**
     * Возвращает уникальное имя для сохраняемого файла,
     * если такое имя уже есть, то происходит рекурсивный вызов
     *
     * @param string $fileExtension
     * @param int $nameLength
     * @return mixed|string
     * @throws \yii\base\Exception
     */
    public function generateFileHashName(string $fileExtension, int $nameLength = 16)
    {
        $hashName = Yii::$app->security->generateRandomString($nameLength) . '.' . $fileExtension;

        $file = TaskFileRepository::instance()->findOne(['hash_name' => $hashName]);

        return (!$file) ? $hashName : $this->generateFileHashName($fileExtension) ;
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