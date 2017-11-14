<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 14.11.17
 * Time: 14:19
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class Project extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%project}}';
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

    public function rules()
    {
        return [
            [['name', 'company_id'], 'required'],
            [['name'], 'string', 'min' => 2, 'max' => 255],
            [['company_id'], 'integer'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['default_visibility_area'], 'integer'],
            [['default_visibility_area'], 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(),['id' => 'company_id']);
    }
}