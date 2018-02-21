<?php

use common\components\widgets\ProjectSearchWidget;
use yii\grid\GridView;
use common\components\dataproviders\EntityDataProvider;
use common\models\entities\TaskEntity;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var EntityDataProvider $actualTasksDataProvider
 * @var EntityDataProvider $lastTasksDataProvider
 */

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="center-block">
            <div class="space-bottom-2x">
                <?= ProjectSearchWidget::widget([]) ?>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?=
                    GridView::widget([
                        'dataProvider' => $actualTasksDataProvider,
                        'layout'=>"{items}",
                        'options' => ['class' => 'text-center'],
                        'headerRowOptions' => ['class' => 'center-header-text'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'title',
                                'header' => 'Проект',
                                'value' => function (TaskEntity $task) {
                                    return $task->getProject()->getName(true);
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'title',
                                'header' => 'Наиболее обсуждаемые',
                                'value' => function (TaskEntity $task) {
                                    return Html::a($task->getTitle(true), ['/task/view', 'id' => $task->getId()]);
                                },
                                'format' => 'html'
                            ],
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?=
                    GridView::widget([
                        'dataProvider' => $lastTasksDataProvider,
                        'layout'=>"{items}",
                        'options' => ['class' => 'text-center'],
                        'headerRowOptions' => ['class' => 'center-header-text'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'title',
                                'header' => 'Проект',
                                'value' => function (TaskEntity $task) {
                                    return $task->getProject()->getName(true);
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'title',
                                'header' => 'Последние',
                                'value' => function (TaskEntity $task) {
                                    return Html::a($task->getTitle(true), ['/task/view', 'id' => $task->getId()]);
                                },
                                'format' => 'html'
                            ],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
