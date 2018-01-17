<?php
use common\models\entities\ParticipantEntity;

/**
 * @var ParticipantEntity $participant
 */
?>

<div>

    <?php if ($participant->getRoleName() === ParticipantEntity::ROLE_USER): ?>

        <span class="label label-success"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php elseif ($participant->getRoleName() === ParticipantEntity::ROLE_MANAGER): ?>

        <span class="label label-warning"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php elseif ($participant->getRoleName() === ParticipantEntity::ROLE_PROJECT_DIRECTOR): ?>

        <span class="label label-primary"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php elseif ($participant->getRoleName() === ParticipantEntity::ROLE_COMPANY_DIRECTOR): ?>

        <span class="label label-info"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php elseif ($participant->getRoleName() === ParticipantEntity::ROLE_ON_CONSIDERATION): ?>

        <span class="label label-default"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php elseif ($participant->getRoleName() === ParticipantEntity::ROLE_BLOCKED): ?>

        <span class="label label-danger"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php elseif ($participant->getRoleName() === ParticipantEntity::ROLE_UNDEFINED): ?>

        <span class="label label-danger"><?= $participant->getRoleNameOnRussian() ?></span>

    <?php endif; ?>
</div>
