<?php

use yii\helpers\Html;
use common\models\searchmodels\task\TaskSearchForm;
use common\components\widgets\RoleViewWidget;
use common\models\entities\ProjectEntity;
use common\components\helpers\LinkHelper;

/**
 * @var \common\models\entities\ParticipantEntity[] $participants
 */

$this->title = 'Проекты';
?>

<div class="row">

    <?php if(!$participants): ?>

        <h3>Вы не учавствуете ни в одном проекте <?=Html::a('найти?', ['/project/search', 'projectName' => '']) ?></h3>

    <?php endif; ?>

    <?php foreach ($participants as $participant) : ?>

        <?php
            $project = $participant->getProject();
        ?>

        <div class="col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <?= Html::a(HTML::encode($project->getName(true)), ['project/view', 'id' => $project->getId()]) ?>
                    <?= RoleViewWidget::widget(['participant' => $participant]) ?>
                </div>
                <?php if($participant->getApproved() || $project->getDefaultVisibilityArea() === ProjectEntity::VISIBILITY_AREA_ALL): ?>
                <div class="panel-body">
                    <p><?= Html::a('Количество задач : ' . $project->getAmountTasks() , LinkHelper::getLinkOnActionTaskIndex($project, TaskSearchForm::STATUS_ALL)) ?></p>
                    <p><?= Html::a('Завершенные : ' . $project->getAmountCompletedTasks(), LinkHelper::getLinkOnActionTaskIndex($project,  TaskSearchForm::STATUS_COMPLETED)) ?></p>
                    <p><?= Html::a('Не завершенные : ' . $project->getAmountNotCompletedTasks(), LinkHelper::getLinkOnActionTaskIndex($project, TaskSearchForm::STATUS_NOT_COMPLETED)) ?></p>
                    <p><?= Html::a('Мои задачи : ' . $project->getAmountTasksByAuthor($participant->getUser()), ['task/index', 'TaskSearchForm[projectId]' => $project->getId(),'TaskSearchForm[status]' => TaskSearchForm::STATUS_OWN]) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>
