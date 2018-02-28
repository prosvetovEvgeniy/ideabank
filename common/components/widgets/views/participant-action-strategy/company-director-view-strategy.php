<?php

use yii\helpers\Html;
use common\models\entities\ParticipantEntity;
use frontend\assets\ProjectParticipantsAsset;

/**
 * @var ParticipantEntity $participant
 * @var string $blockTag
 * @var string $unBlockTag
 * @var string $addTag
 * @var string $cancelTag
 */

ProjectParticipantsAsset::register($this);

if ($participant->isBlocked()) {
    echo Html::tag('div', $unBlockTag, ['data' => ['participant-id' => $participant->getId()]]);
} elseif (!$participant->isCompanyDirector() && !$participant->onConsideration()) {
    echo Html::tag('div', $blockTag, ['data' => ['participant-id' => $participant->getId()]]);
} elseif ($participant->onConsideration()) {
    echo Html::tag('div', $addTag . $cancelTag, ['data' => ['participant-id' => $participant->getId()]]);
}
