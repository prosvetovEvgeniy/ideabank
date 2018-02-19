<?php

namespace frontend\models\participant;

use common\components\facades\ParticipantFacade;
use common\models\repositories\participant\ParticipantRepository;
use yii\base\Model;
use Exception;

/**
 * Class UnBlockParticipantModel
 * @package frontend\models\participant
 *
 * @property int $id
 */
class UnBlockParticipantModel extends Model
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
     */
    public function save()
    {
        if (!$this->validate()){
            return false;
        }

        $participant = ParticipantRepository::instance()->findOne(['id' => $this->id]);

        $participantFacade = new ParticipantFacade();
        
        try{
            $participantFacade->unBlock($participant);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}