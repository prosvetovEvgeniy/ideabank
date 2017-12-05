<?php

use yii\helpers\Html;
use common\models\searchmodels\TaskEntitySearch;

/**
 * @var \common\models\entities\ParticipantEntity[] $participants
 */
?>

<div class="row">

    <?php foreach ($participants as $participant) : ?>

        <?php
            $project = $participant->getProject();
        ?>

        <div class="col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">

                    <?= Html::a(HTML::encode($project->getName()), ['project/view', 'projectName' => $project->getName()], ['target' => '_blank']) ?>

                    <div><span class="label label-success"><?= $participant->getRoleName() ?></span></div>
                </div>

                <div class="panel-body">
                    <p><?= Html::a('Количество задач : ' . $project->getAmountTasks() , ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_ALL], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Завершенные : ' . $project->getAmountCompletedTasks(), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_COMPLETED], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Не завершенные : ' . $project->getAmountNotCompletedTasks(), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_NOT_COMPLETED], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Мои задачи : ' . $project->getAmountTasksByAuthor($participant->getUser()), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(),'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_OWN], ['target' => '_blank']) ?></p>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>
