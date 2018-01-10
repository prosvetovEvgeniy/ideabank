<?php

namespace frontend\models\task;


use common\models\activerecords\Project;
use common\models\activerecords\Users;
use common\models\entities\TaskEntity;
use common\models\entities\TaskFileEntity;
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
 * @property string $title
 * @property string $content
 * @property int    $authorId
 * @property int    $projectId
 * @property int    $status
 * @property int    $visibilityArea
 * @property UploadedFile[] $files
 *
 * @property int $taskId
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

    protected $taskId;

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

    public function save()
    {
        try
        {
            $project = ProjectRepository::instance()->findOne(['id' => $this->projectId]);

            $task = new TaskEntity($this->title, $this->content, $this->authorId, $this->projectId,
                             TaskEntity::STATUS_ON_CONSIDERATION, $project->getDefaultVisibilityArea());

            $transaction = Yii::$app->db->beginTransaction();

            //сохраняем задачу
            $this->taskId = (TaskRepository::instance()->add($task))->getId();

            if($this->files) //если есть файлы сохраняем их тоже
            {
                foreach ($this->files as $file)
                {
                    $hashName = $this->generateFileHashName($file->extension);

                    $taskFile = new TaskFileEntity($this->taskId, $hashName, $file->name);

                    TaskFileRepository::instance()->add($taskFile);

                    //если файл не сохранился, откатываем транзакцию
                    if(!$file->saveAs(TaskFileEntity::PATH_TO_FILE . $hashName))
                    {
                        $transaction->rollBack();

                        return false;
                    }
                }
            }

            $transaction->commit(); //если все успешно сохраняем данные

            return true;
        }
        catch (Exception $e)
        {
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
     */
    public function generateFileHashName(string $fileExtension, int $nameLength = 16)
    {
        $hashName = Yii::$app->security->generateRandomString($nameLength) . '.' . $fileExtension;

        $file = TaskFileRepository::instance()->findOne(['hash_name' => $hashName]);

        return (!$file) ? $hashName : $this->generateFileHashName($fileExtension) ;
    }

    /**
     * Используется для редиректа в контроллере на созданную задачу
     *
     * @return int
     */
    public function getTaskId() { return $this->taskId; }
}