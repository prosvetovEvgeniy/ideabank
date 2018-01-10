<?php

namespace common\components\widgets;


use common\models\entities\ParticipantEntity;
use yii\base\Exception;
use yii\base\Widget;

/**
 * Class RoleViewWidget
 * @package common\components\widgets
 *
 * @property ParticipantEntity $participant
 */
class RoleViewWidget extends Widget
{
    public $participant;

    public function init()
    {
        parent::init();

        if($this->participant === null)
        {
            throw new Exception('Participant must be set');
        }
    }

    public function run()
    {
        return $this->render('role-view',['participant' => $this->participant]);
    }
}