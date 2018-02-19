<?php

use common\components\dataproviders\EntityDataProvider;
use yii\helpers\Html;
use common\models\searchmodels\task\TaskSearchForm;
use common\models\entities\TaskEntity;
use yii\grid\GridView;
use common\models\entities\ProjectEntity;
use common\models\entities\ParticipantEntity;

/**
 * @var TaskSearchForm $searchModel
 * @var EntityDataProvider $dataProvider
 * @var ProjectEntity $currentProject
 * @var ParticipantEntity[] $participants
 */

$this->title = 'Предложения';
?>

<div class="row">

    <div class="col-md-2 col-sm-12">
        <div class="btn-group-vertical" role="group">
            <?php foreach ($participants as $participant): ?>

                <?php $project = $participant->getProject(); ?>

                <?= Html::a($project->getName(), ['task/index', 'TaskSearchForm[projectId]' => $project->getId(), 'TaskSearchForm[status]' => TaskSearchForm::STATUS_ALL, 'projectId' => $project->getId()], ['class' => 'btn btn-default']) ?>

            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-md-10 col-sm-12">

        <h2 class="no-margin-top margin-bottom"><?= $currentProject->getName() ?></h2>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{items}\n{pager}",
            'options' => ['class' => 'text-center'],
            'headerRowOptions' => ['class' => 'center-header-text'],
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'header' => 'Заголовок',
                    'value' => function(TaskEntity $task) {
                        return Html::a($task->getTitle(), ['task/view', 'id' => $task->getId(), 'projectId' => $task->getProjectId()]);
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Статус',
                    'value' => function(TaskEntity $task) { return $task->getStatusAsText(); },
                ],
                [
                    'attribute' => 'creationDate',
                    'header' => 'Дата создания',
                    'value' => function(TaskEntity $task) {
                        return Html::tag('code', $task->getCreatedDate());
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'plannedEndDate',
                    'header' => 'Планируемая дата завершения',
                    'value' => function(TaskEntity $task) {
                        return Html::tag('code', $task->getPlannedEndDate());
                    },
                    'format' => 'html'
                ],
            ]
        ]);
        ?>
    </div>
</div>


