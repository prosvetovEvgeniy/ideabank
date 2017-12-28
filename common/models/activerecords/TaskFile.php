<?php

namespace common\models\activerecords;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_file".
 *
 * @property integer $id
 * @property integer $task_id
 * @property string  $hash_name
 * @property string  $original_name
 * @property integer $created_at
 * @property boolean $deleted
 *
 * @property Task $task
 */
class TaskFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_file';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => time(),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'hash_name', 'original_name'], 'required'],
            [['task_id', 'created_at'], 'integer'],
            [['deleted'], 'boolean'],
            [['hash_name', 'original_name'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'hash_name' => 'Hash Name',
            'original_name' => 'Original Name',
            'created_at' => 'Created At',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
