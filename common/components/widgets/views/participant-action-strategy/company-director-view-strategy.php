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

if ($participant->hasBlockedRole()) {
    echo Html::tag('div', $unBlockTag, ['data' => ['participant-id' => $participant->getId()]]);
} elseif (!$participant->hasCompanyDirectorRole() && !$participant->hasOnConsiderationRole()) {
    echo Html::tag('div', $blockTag, ['data' => ['participant-id' => $participant->getId()]]);
} elseif ($participant->hasOnConsiderationRole()) {
    echo Html::tag('div', $addTag . $cancelTag, ['data' => ['participant-id' => $participant->getId()]]);
}
