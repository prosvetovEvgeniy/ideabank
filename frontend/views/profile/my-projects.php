<?php

use common\components\widgets\ProfileMenuWidget;
use frontend\assets\SubMenuAsset;
use common\models\entities\ParticipantEntity;
use common\components\widgets\RoleViewWidget;
use yii\helpers\Html;
use frontend\assets\ProfileProjectsAsset;

SubMenuAsset::register($this);
ProfileProjectsAsset::register($this);

/**
 * @var ParticipantEntity[] $participants
 * @var ParticipantEntity[] $deletedParticipants
 */
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= ProfileMenuWidget::widget() ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-9">
        <div class="dialogs-block">

            <h3 class="header">Текущие</h3>

            <table class="table dialogs">
                <thead>
                    <tr>
                        <th scope="col">Компания</th>
                        <th scope="col">Проект</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Дата вступления</th>
                    </tr>
                </thead>

                <?php foreach ($participants as $participant): ?>

                    <tbody>
                        <tr>
                            <td><?= $participant->getCompany()->getName() ?></td>
                            <td><?= Html::a($participant->getProject()->getName(), ['project/view', 'id' => $participant->getProjectId()]) ?></td>
                            <td><?= RoleViewWidget::widget(['participant' => $participant]) ?></td>
                            <td><code><?= $participant->getUpdatedAtDate() ?></code></td>
                            <td><a class="leave-project" data-participant-id="<?= $participant->getId() ?>" href="">Покинуть</a></td>
                        </tr>
                    </tbody>

                <?php endforeach; ?>
            </table>
        </div>

        <?php if (!empty($deletedParticipants)): ?>

            <div class="dialogs-block">

                <h3 class="header-deleted">Удаленные</h3>

                <table class="table dialogs">
                    <thead>
                    <tr>
                        <th scope="col">Компания</th>
                        <th scope="col">Проект</th>
                        <th scope="col">Дата выхода</th>
                    </tr>
                    </thead>

                    <?php foreach ($deletedParticipants as $participant): ?>

                        <tbody>
                        <tr>
                            <td><?= $participant->getCompany()->getName() ?></td>
                            <td><?= Html::a($participant->getProject()->getName(), ['project/view', 'id' => $participant->getProjectId()]) ?></td>
                            <td><code><?= $participant->getDeletedAtDate() ?></code></td>
                            <td>
                                <a class="join-to-project" data-participant-id="<?= $participant->getId() ?>" href="">Присоединиться</a> /
                                <a class="delete-participant" data-participant-id="<?= $participant->getId() ?>" href="">Очистить</a>
                            </td>
                        </tr>
                        </tbody>

                    <?php endforeach; ?>
                </table>
            </div>

        <?php endif; ?>

    </div>
</div>