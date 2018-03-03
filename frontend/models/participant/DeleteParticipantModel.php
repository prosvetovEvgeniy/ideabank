<?php

namespace frontend\models\participant;

use common\components\facades\ParticipantFacade;
use common\components\helpers\ParticipantHelper;
use common\models\repositories\participant\ParticipantRepository;
use yii\base\Model;
use Yii;
use Exception;

/**
 * Class DeleteParticipantModel
 * @package frontend\models\profile
 *
 * @property int $id
 */
class DeleteParticipantModel extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function delete()
    {
        if (!$this->validate()) {
            return false;
        }

        $participant = ParticipantRepository::instance()->findOne(['id' => $this->id]);

        if (!$participant || $participant->getDeleted()) {
            return false;
        }

        $participantFacade = new ParticipantFacade();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $participant = $participantFacade->deleteParticipant($participant);

            if (!ParticipantHelper::addOrUpdateRoleCache($participant)) {
               throw new Exception();
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}