<?php

namespace frontend\models\participant;

use yii\base\Model;
use Yii;
use common\models\repositories\project\ProjectRepository;
use common\models\repositories\participant\ParticipantRepository;
use Exception;
use common\models\entities\ParticipantEntity;

/**
 * Class JoinParticipantModel
 * @package frontend\models\participant
 *
 * @property int $projectId
 * @property int $userId
 */
class JoinParticipantModel extends Model
{
    public $projectId;
    public $userId;

    public function rules()
    {
        return [
            [['projectId', 'userId'], 'required'],
            [['projectId', 'userId'], 'integer'],
            [['userId'], 'filter', 'filter' => function($value) {
                return (int) $value;
            }]
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = Yii::$app->user->identity;
        $project = ProjectRepository::instance()->findOne(['id' => $this->projectId]);

        if ($this->userId !== $user->getId() || !$project) {
            return false;
        }

        $participantExist = ParticipantRepository::instance()->findOne([
            'user_id'    => $user->getId(),
            'project_id' => $project->getId(),
        ]);

        //если пользователь уже был присоединен к проекту, но покинул его
        if ($participantExist) {
            if($participantExist->getDeleted()) {

                $participantExist->setApproved(false);
                $participantExist->setApprovedAt(time());
                $participantExist->setDeleted(false);
                $participantExist->setDeletedAt();

                try {
                    ParticipantRepository::instance()->update($participantExist);
                    return true;
                } catch (Exception $e) {
                    return false;
                }
            }

            return false;
        }

        //если пользователь присоединяется к проекту впервые
        $participant = new ParticipantEntity($user->getId(), $project->getCompanyId(), $project->getId());

        try {
            ParticipantRepository::instance()->add($participant);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}