<?php

namespace common\models\searchmodels\project;


use common\components\dataproviders\EntityDataProvider;
use common\components\helpers\EntityHelper;
use common\models\interfaces\ISearchEntityModel;
use common\models\repositories\participant\ParticipantViewRepository;
use common\models\repositories\user\UserRepository;
use yii\base\Model;

/**
 * Class ParticipantSearchForm
 * @package frontend\models\project
 *
 * @property string $username
 * @property string $firstName
 * @property string $secondName
 * @property string $email
 * @property string $role
 * @property int    $projectId
 */
class ParticipantSearchForm extends Model implements ISearchEntityModel
{
    public $username;
    public $firstName;
    public $secondName;
    public $email;
    public $role;
    public $projectId;

    public function rules()
    {
        return [
            [['projectId'], 'required'],

            [['username'], 'string'],
            [['username'], 'default', 'value' => ''],

            [['projectId'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'  => 'Логин',
            'projectId' => 'Проект'
        ];
    }

    /**
     * @param int $pageSize
     * @return EntityDataProvider
     */
    public function search(int $pageSize = 20): EntityDataProvider
    {
        return new EntityDataProvider([
            'condition' => [
                'and',
                ['project_id' => $this->projectId],
                ['like', 'username', $this->username]
            ],
            'repositoryInstance' => ParticipantViewRepository::instance()
        ]);
    }
}