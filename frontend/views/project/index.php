<?php

use yii\helpers\Html;
use common\models\searchmodels\TaskEntitySearch;
use common\components\widgets\RoleViewWidget;

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
                    <?= Html::a(HTML::encode($project->getName()), ['project/view', 'id' => $project->getId()], ['target' => '_blank']) ?>
                    <?= RoleViewWidget::widget(['participant' => $participant]) ?>
                </div>

                <div class="panel-body">
                    <p><?= Html::a('Количество задач : ' . $project->getAmountTasks() , ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_ALL]) ?></p>
                    <p><?= Html::a('Завершенные : ' . $project->getAmountCompletedTasks(), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_COMPLETED]) ?></p>
                    <p><?= Html::a('Не завершенные : ' . $project->getAmountNotCompletedTasks(), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_NOT_COMPLETED]) ?></p>
                    <p><?= Html::a('Мои задачи : ' . $project->getAmountTasksByAuthor($participant->getUser()), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(),'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_OWN]) ?></p>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>
