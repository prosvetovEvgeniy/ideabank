<?php

namespace common\models\searchmodels\participant;

use common\components\dataproviders\EntityDataProvider;
use common\models\entities\AuthAssignmentEntity;
use common\models\entities\ParticipantEntity;
use common\models\interfaces\ISearchEntityModel;
use common\models\repositories\participant\ParticipantViewRepository;
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
 * @property string $phone
 * @property int    $projectId
 */
class ParticipantSearchForm extends Model implements ISearchEntityModel
{
    public $username;
    public $firstName;
    public $secondName;
    public $email;
    public $role;
    public $phone;
    public $projectId;

    public function rules()
    {
        return [
            [['projectId'], 'required'],

            [['username'], 'string'],
            [['username'], 'default', 'value' => null],

            [['firstName'], 'string'],
            [['firstName'], 'default', 'value' => null],

            [['secondName'], 'string'],
            [['secondName'], 'default', 'value' => null],

            [['email'], 'string'],
            [['email'], 'default', 'value' => null],

            [['role'], 'string'],
            [['role'], 'default', 'value' => null],
            [['role'], 'in', 'range' => array_keys(AuthAssignmentEntity::LIST_ROLES)],

            [['phone'], 'string'],
            [['phone'], 'default', 'value' => null],

            [['projectId'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'     => 'Логин',
            'firstName'    => 'Имя',
            'secondName'   => 'Фамилия',
            'email'        => 'Email',
            'role'         => 'Роль',
            'projectId'    => 'Проект',
            'phone'        => 'Номер телефона'
        ];
    }

    /**
     * @param int $pageSize
     * @return EntityDataProvider
     */
    public function search(int $pageSize = 20): EntityDataProvider
    {
        return new EntityDataProvider([
            'condition' => $this->buildCondition(),
            'repositoryInstance' => ParticipantViewRepository::instance(),
            'pagination' => [
                'pageSize' => $pageSize
            ],
            'with' => ['user', 'project', 'authAssignment']
        ]);
    }

    /**
     * @return array
     */
    private function buildCondition()
    {
        $condition = [
            'and',
            ['project_id' => $this->projectId],
            ['participant.deleted' => false],
            ['users.deleted' => false]
        ];

        if ($this->username) {
            array_push($condition, ['like', 'lower(username)', mb_strtolower($this->username)]);
        }

        if ($this->firstName) {
            array_push($condition, ['like', 'lower(first_name)', mb_strtolower($this->firstName)]);
        }

        if ($this->secondName) {
            array_push($condition, ['like', 'lower(second_name)', mb_strtolower($this->secondName)]);
        }

        if ($this->email) {
            array_push($condition, ['like', 'lower(email)', mb_strtolower($this->email)]);
        }

        if ($this->phone) {
            array_push($condition, ['like', 'phone', $this->phone]);
        }

        if ($this->role !== null) {
            if ($this->role === AuthAssignmentEntity::ROLE_ON_CONSIDERATION){
                array_push($condition, ['blocked' => false, 'approved' => false]);
            } else if ($this->role === AuthAssignmentEntity::ROLE_BLOCKED) {
                array_push($condition, ['blocked' => true]);
            } else {
                array_push($condition, ['item_name' => $this->role]);
            }
        }

        return $condition;
    }
}