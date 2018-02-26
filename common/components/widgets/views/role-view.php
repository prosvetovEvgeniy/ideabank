<?php

use common\models\entities\ParticipantEntity;
use common\models\entities\AuthAssignmentEntity;

/**
 * @var ParticipantEntity $participant
 */
?>

<div>

    <?php $role = $participant->getRoleName(); ?>

    <?php if ($participant->isUser()): ?>

        <span class="label label-success"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->isManager()): ?>

        <span class="label label-warning"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->isProjectDirector()): ?>

        <span class="label label-primary"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->isCompanyDirector()): ?>

        <span class="label label-info"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->onConsideration()): ?>

        <span class="label label-default"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->getBlocked()): ?>

        <span class="label label-danger"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php endif; ?>
</div>
