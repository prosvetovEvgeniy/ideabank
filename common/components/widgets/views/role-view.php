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

        <span class="label label-success"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->isManager()): ?>

        <span class="label label-warning"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->isProjectDirector()): ?>

        <span class="label label-primary"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->isCompanyDirector()): ?>

        <span class="label label-info"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->onConsideration()): ?>

        <span class="label label-default"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php elseif ($participant->blocked()): ?>

        <span class="label label-danger"><?= AuthAssignmentEntity::LIST_ROLES[$role] ?></span>

    <?php endif; ?>
</div>
