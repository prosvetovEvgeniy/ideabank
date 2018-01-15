<?php

namespace common\models\activerecords;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $author_id
 * @property integer $project_id
 * @property integer $status
 * @property integer $visibility_area
 * @property integer $parent_id
 * @property integer $planned_end_at
 * @property integer $end_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $deleted
 *
 * @property Comment[] $comments
 * @property Project $project
 * @property Users $author
 * @property TaskLike[] $taskLikes
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->deleted = false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'author_id', 'project_id'], 'required'],
            [['content'], 'string'],
            [['author_id', 'project_id', 'status', 'visibility_area', 'parent_id', 'planned_end_at', 'end_at', 'created_at', 'updated_at'], 'integer'],
            [['deleted'], 'boolean'],
            [['title'], 'string', 'max' => 255],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}
