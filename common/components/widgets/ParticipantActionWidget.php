<?php

namespace common\components\widgets;

use common\models\entities\ParticipantEntity;
use yii\base\NotSupportedException;
use yii\base\Widget;
use Exception;
use Yii;
use yii\helpers\Html;

/**
 * Class ParticipantActionWidget
 * @package common\components\widgets
 *
 * @property ParticipantEntity $participant
 */
class ParticipantActionWidget extends Widget
{
    public $participant;

    public function init()
    {
        parent::init();

        if ($this->participant === null) {
            throw new Exception('Participant must be set');
        }
    }

    public function run()
    {
        $config = [
            'participant' => $this->participant,
            'blockTag'    => $this->getBlockTag(),
            'unBlockTag'  => $this->getUnBlockTag(),
            'addTag'      => $this->getAddTag(),
            'cancelTag'   => $this->getCancelTag()
        ];

        if (Yii::$app->user->isCompanyDirector($this->participant->getProjectId())) {
            return $this->render('participant-action-strategy/company-director-view-strategy', $config);
        } elseif (Yii::$app->user->isProjectDirector($this->participant->getProjectId())) {
            return $this->render('participant-action-strategy/project-director-view-strategy', $config);
        } elseif (Yii::$app->user->isManager($this->participant->getProjectId())) {
            return $this->render('participant-action-strategy/manager-view-strategy', $config);
        } else {
            throw new NotSupportedException();
        }
    }

    /**
     * @return string
     */
    private function getBlockTag()
    {
        return Html::tag('i', '', ['class' => 'glyphicon glyphicon-ban-circle participant-action-tag block-tag', 'title' => 'Заблокировать']);
    }

    /**
     * @return string
     */
    private function getUnBlockTag()
    {
        return Html::tag('i', '', ['class' => 'glyphicon glyphicon-refresh participant-action-tag un-block-tag', 'title' => 'Разблокировать']);
    }

    /**
     * @return string
     */
    private function getAddTag()
    {
        return Html::tag('i', '', ['class' => 'glyphicon glyphicon-ok participant-action-tag add-tag', 'title' => 'Разблокировать']);
    }

    /**
     * @return string
     */
    private function getCancelTag()
    {
        return Html::tag('i', '', ['class' => 'glyphicon glyphicon-remove participant-action-tag cancel-tag', 'title' => 'Не добавлять']);
    }
}