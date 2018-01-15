<?php

namespace frontend\models\project;

use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;
use yii\base\Model;
use Yii;
use common\models\repositories\ProjectRepository;
use common\models\repositories\ParticipantRepository;

/**
 * Class JoinToProjectModel
 * @package frontend\models\project
 *
 * @property int $projectId
 * @property int $userId
 */
class JoinToProjectModel extends Model
{
    public $projectId;
    public $userId;

    public function rules()
    {
        return [
            [['projectId', 'userId'], 'required'],
            [['projectId', 'userId'], 'integer']
        ];
    }

    public function save()
    {
        if(!$this->validate())
        {
            return false;
        }

        /**
         * @var UserEntity $user
         */
        $user = Yii::$app->user->identity->getUser();
        $project = ProjectRepository::instance()->findOne(['id' => $this->projectId]);

        if($this->userId !== $user->getId() || !$project)
        {
            return false;
        }

        $participantExist = ParticipantRepository::instance()->findOne([
            'user_id'    => $user->getId(),
            'project_id' => $project->getId(),
        ]);

        if($participantExist)
        {
            return false;
        }

        $participant = new ParticipantEntity($this->userId, $project->getCompanyId(), $project->getId());

        if(!ParticipantRepository::instance()->add($participant))
        {
            return false;
        }

        return true;
    }
}