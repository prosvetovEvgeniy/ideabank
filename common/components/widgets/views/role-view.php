<?php

use common\models\entities\ParticipantEntity;
use common\models\entities\AuthAssignmentEntity;

/**
 * @var ParticipantEntity $participant
 */
?>

<div>

    <?php $role = $participant->getRoleName(); ?>

    <?php if ($role === AuthAssignmentEntity::ROLE_USER): ?>

        <span class="label label-success"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($role === AuthAssignmentEntity::ROLE_MANAGER): ?>

        <span class="label label-warning"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($role === AuthAssignmentEntity::ROLE_PROJECT_DIRECTOR): ?>

        <span class="label label-primary"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($role === ParticipantEntity::ROLE_ON_CONSIDERATION): ?>

        <span class="label label-default"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($role === ParticipantEntity::ROLE_BLOCKED): ?>

        <span class="label label-danger"><?= ParticipantEntity::LIST_ROLES[$role] ?></span>

    <?php endif; ?>
</div>
