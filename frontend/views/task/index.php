<?php

use common\components\dataproviders\EntityDataProvider;
use yii\helpers\Html;
use common\models\searchmodels\TaskEntitySearch;
use common\models\entities\TaskEntity;
use yii\grid\GridView;

/**
 * @var EntityDataProvider $dataProvider
 * @var \common\models\searchmodels\TaskEntitySearch $searchModel
 * @var \common\models\entities\ProjectEntity $currentProject
 * @var \common\models\entities\ParticipantEntity[] $participants
 */
?>

<div class="row">

    <div class="col-md-2 col-sm-12">

        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Проект
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">

                <?php foreach ($participants as $participant): ?>

                    <?php $project = $participant->getProject(); ?>

                    <li><?= Html::a($project->getName(), ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_ALL]) ?></a></li>

                <?php endforeach; ?>

            </ul>
        </div>

    </div>

    <div class="col-md-10 col-sm-12">

        <h2 class="no-margin-top margin-bottom"><?= $currentProject->getName() ?></h2>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{items}\n{pager}",
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'header' => 'Заголовок',
                    'value' => function(TaskEntity $task) {
                        return Html::a($task->getTitle(), ['task/view', 'taskId' => $task->getId()]);
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


