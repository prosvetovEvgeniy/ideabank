<?php

namespace common\models\activerecords;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $deleted
 *
 * @property Participant[] $participants
 * @property Project[] $projects
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->deleted = false;
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['deleted'], 'boolean'],
        ];
    }
}
