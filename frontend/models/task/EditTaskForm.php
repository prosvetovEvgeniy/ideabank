<?php

namespace frontend\models\task;

use common\components\facades\TaskFacade;
use common\models\entities\TaskEntity;
use yii\base\Model;
use yii\db\Exception;
use yii\web\UploadedFile;
use common\models\entities\TaskFileEntity;
use Yii;

/**
 * Class EditTaskForm
 * @package frontend\models\task
 *
 * @property string         $title;
 * @property string         $content
 * @property int            $projectId
 * @property UploadedFile[] $files
 * @property int            $status
 * @property int            $visibilityArea
 * @property int            $plannedEndAt
 * @property int            $parentId
 *
 * @property TaskEntity     $task
 */
class EditTaskForm extends Model
{
    public const SCENARIO_ADMIN_EDIT = 'scenario admin edit';
    public const DATE_FORMAT = 'dd.MM.yyyy';

    public $title;
    public $content;
    public $files;
    public $status;
    public $visibilityArea;
    public $plannedEndAt;
    public $parentId;

    //сущность сохраненой задачи
    private $task;

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

        if (Yii::$app->user->isManager($task->getProjectId())){
            $this->status = $task->getStatus();
            $this->visibilityArea = $task->getVisibilityArea();
            $this->plannedEndAt = $task->getPlannedEndAt();
            $this->parentId = $task->getParentId();
        }

        $this->task = $task;
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['title', 'content', 'status', 'visibilityArea'], 'required', 'on' => self::SCENARIO_ADMIN_EDIT],

            [['title'], 'string', 'length' => [
                TaskEntity::TITLE_MIN_LENGTH,
                TaskEntity::TITLE_MAX_LENGTH
            ]],

            [['content'], 'string', 'length' => [
                TaskEntity::CONTENT_MIN_LENGTH,
                TaskEntity::CONTENT_MAX_LENGTH
            ]],

            [['status'], 'integer'],
            [['status'], 'in', 'range' => [
                TaskEntity::STATUS_ON_CONSIDERATION,
                TaskEntity::STATUS_IN_PROGRESS,
                TaskEntity::STATUS_COMPLETED,
                TaskEntity::STATUS_MERGED
            ]],
            [['status'], 'filter', 'filter' => function($value){
                return ($value != '') ? (int) $value : null ;
            }],

            [['visibilityArea'], 'integer'],
            [['visibilityArea'], 'in', 'range' => [
                TaskEntity::VISIBILITY_AREA_ALL,
                TaskEntity::VISIBILITY_AREA_REGISTERED,
                TaskEntity::VISIBILITY_AREA_PRIVATE
            ]],
            [['visibilityArea'], 'filter', 'filter' => function($value){
                return ($value != '') ? (int) $value : null ;
            }],

            [['plannedEndAt'], 'default', 'value' => null],
            [['plannedEndAt'], 'date', 'format' => self::DATE_FORMAT, 'timestampAttribute' => 'plannedEndAt'],

            [['parentId'], 'integer'],
            [['parentId'], 'filter', 'filter' => function($value){
                return ($value != '') ? (int) $value : null ;
            }],
            [['parentId'], 'checkOnStatus', 'skipOnEmpty' => false],

            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => TaskFileEntity::MAX_FILES_TO_TASK, 'maxSize' => TaskFileEntity::MAX_SIZE_FILES] //maxSize = 100MB
        ];
    }

    public function checkOnStatus($attribute)
    {
        if($this->$attribute !== null){
            $this->status = TaskEntity::STATUS_MERGED;
        }

        if($this->$attribute === null && $this->status === TaskEntity::STATUS_MERGED){
            $this->addError($attribute, 'Задача не имеет родителя, но помечена как объединенная');
        }
    }

    public function attributeLabels()
    {
        return [
            'title'          => 'Заголовок',
            'content'        => 'Содержание',
            'files'          => 'Добавить файлы',
            'status'         => 'Статус',
            'visibilityArea' => 'Область видимости',
            'plannedEndAt'   => 'Планируемая дата завершения',
            'parentId'       => 'Родительская задача'
        ];
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function update()
    {
        if(!$this->validate()) {
            return false;
        }

        $this->task->setTitle($this->title);
        $this->task->setContent($this->content);

        if(Yii::$app->user->isManager($this->task->getProjectId())){
            $this->task->setStatus($this->status);
            $this->task->setVisibilityArea($this->visibilityArea);
            $this->task->setPlannedEndAt($this->plannedEndAt);
            $this->task->setParentId($this->parentId);

            if($this->status === TaskEntity::STATUS_COMPLETED){
                $this->task->setEndAt(time());
            }
        }

        $taskFacade = new TaskFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->task = $taskFacade->editTask($this->task, $this->files);

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