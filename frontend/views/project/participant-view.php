<?php

use common\models\entities\ParticipantEntity;
use yii\helpers\Html;
use common\components\widgets\RoleViewWidget;
use common\components\helpers\ParticipantHelper;

/**
 * @var ParticipantEntity $participant
 * @var ParticipantEntity $selfParticipant
 */
?>

<div class="row">
    <div class="col-lg-6">
        <div class="statistics-block no-border">
            <div class="profile">
                <div>
                    <?= Html::img($participant->getUser()->getAvatarAlias(), ['class' => 'avatar']) ?>
                </div>
                <?= RoleViewWidget::widget(['participant' => $participant]) ?>
                <div class="inf-block">
                    <h4>Логин: <?= $participant->getUser()->getUsername(true) ?></h4>
                </div>


                <?php if ($participant->hasBlockedRole()): ?>

                <?php elseif ($participant->hasOnConsiderationRole()): ?>

                <?php else: ?>

                    <?php if ($participant->hasUserRole() && $selfParticipant->hasManagerRole()): ?>

                    <?php elseif (ParticipantHelper::canChangeRole($selfParticipant, $participant)): ?>

                    <?php else: ?>

                    <?php endif; ?>

                <?php endif; ?>


                <div class="inf-block">
                    <h4>ФИО: <?= $participant->getUser()->getFio(true) ?></h4>
                </div>
                <div class="profile__btn">
                    <?= Html::a('Написать сообщение', ['/message/chat', 'companionId' => $participant->getUser()->getId()], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="profile__btn">
                    <button class="btn btn-danger">Заблокировать</button>
                </div>
                <div class="profile__btn">
                    <?= Html::a('Изменить роль', ['/message/chat', 'companionId' => $participant->getUser()->getId()], ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="statistics-block">
            <h4>статистика</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="statistics-block">
            <h4>статистика</h4>
        </div>
    </div>
</div>