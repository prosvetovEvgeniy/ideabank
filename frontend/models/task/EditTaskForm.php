<?php

namespace frontend\models\task;


use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class EditTaskForm
 * @package frontend\models\task
 *
 * @property string         $title;
 * @property string         $content
 * @property int            $projectId
 * @property UploadedFile[] $files
 */
class EditTaskForm extends Model
{
    public $title;
    public $content;
    public $projectId;
    public $files;

    public function rules()
    {
        return [
            [['title', 'content', 'projectId'], 'required'],
            [['title', 'content'], 'string'],
            [['projectId'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'projectId' => 'Проект'
        ];
    }


}