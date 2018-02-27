<?php

namespace frontend\models\participant;

use common\components\facades\ParticipantFacade;
use yii\base\Model;
use Yii;
use common\models\repositories\project\ProjectRepository;
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
     * @throws \yii\db\Exception
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

        $participant = new ParticipantEntity(
            $this->userId,
            $project->getCompanyId(),
            $project->getId()
        );

        $participantFacade = new ParticipantFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $participantFacade->joinParticipant($participant);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}