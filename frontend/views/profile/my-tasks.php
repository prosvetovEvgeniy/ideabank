<?php

use common\components\widgets\ProfileMenuWidget;
use frontend\assets\SubMenuAsset;
use yii\grid\GridView;
use common\components\dataproviders\EntityDataProvider;
use common\models\entities\TaskEntity;
use yii\helpers\Html;

SubMenuAsset::register($this);

/**
 * @var EntityDataProvider $dataProvider
 */

$this->title = 'Мои задачи';
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= ProfileMenuWidget::widget() ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-9">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{items}\n{pager}",
            'captionOptions' => ['class' => 'header'],
            'options' => ['class' => 'text-center'],
            'headerRowOptions' => ['class' => 'center-header-text'],
            'columns' =>[

                [
                    'header' => 'Задача',
                    'value' => function(TaskEntity $task) {
                        return Html::a($task->getTitle(),['/task/view', 'id' => $task->getId()]);
                    },
                    'format' => 'html'
                ],
                [
                    'header' => 'Проект',
                    'value' => function(TaskEntity $task) {
                        return $task->getProject()->getName();
                    },
                    'format' => 'html'
                ],
                [
                    'header' => 'На рассмотрении',
                    'value' => function(TaskEntity $task) {
                        $tag = '';

                        if($task->checkStatus(TaskEntity::STATUS_ON_CONSIDERATION))
                        {
                            $tag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-asterisk']);
                        }

                        return $tag;
                    },
                    'format' => 'html'
                ],
                [
                    'header' => 'Выполняется',
                    'value' => function(TaskEntity $task) {
                        $tag = '';

                        if($task->checkStatus(TaskEntity::STATUS_IN_PROGRESS))
                        {
                            $tag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-asterisk']);
                        }

                        return $tag;
                    },
                    'format' => 'html'
                ],
                [
                    'header' => 'Завершена',
                    'value' => function(TaskEntity $task) {
                        $tag = '';

                        if($task->checkStatus(TaskEntity::STATUS_COMPLETED))
                        {
                            $tag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-asterisk']);
                        }

                        return $tag;
                    },
                    'format' => 'html'
                ],
                [
                    'header' => 'Объединена',
                    'value' => function(TaskEntity $task) {
                        $tag = '';

                        if($task->checkStatus(TaskEntity::STATUS_MERGED))
                        {
                            $tag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-asterisk']);
                        }

                        return $tag;
                    },
                    'format' => 'html'
                ]
            ]
        ]);
        ?>
    </div>
</div>