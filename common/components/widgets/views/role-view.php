<?php

use common\models\entities\ParticipantEntity;
use common\models\entities\AuthAssignmentEntity;

/**
 * @var ParticipantEntity $participant
 */
?>

<div class="role_view">

    <?php $role = $participant->getRoleName(); ?>

    <?php if ($participant->hasUserRole()): ?>

        <span class="label label-success"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->hasManagerRole()): ?>

        <span class="label label-warning"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->hasProjectDirectorRole()): ?>

        <span class="label label-primary"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->hasCompanyDirectorRole()): ?>

        <span class="label label-info"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->hasOnConsiderationRole()): ?>

        <span class="label label-default"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->hasBlockedRole()): ?>

        <span class="label label-danger"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php endif; ?>
</div>
