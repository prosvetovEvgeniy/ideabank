<?php

namespace common\models\activerecords;

use yii\db\ActiveRecord;

/**
 * Class TaskNotice
 * @package common\models\activerecords
 *
 * @property integer $id
 * @property integer $task_id;
 * @property integer $notice_id
 * @property Task    $task
 * @property Notice  $notice
 */
class TaskNotice extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%task_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'notice_id'], 'required'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['notice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notice::className(), 'targetAttribute' => ['notice_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotice()
    {
        return $this->hasOne(Notice::className(), ['id' => 'notice_id']);
    }
}