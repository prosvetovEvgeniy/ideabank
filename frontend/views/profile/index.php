<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */
?>

<div class="row">
    <div class="col-lg-6">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,

            'columns' => [
                [
                    'attribute' => 'project_id',
                    'label'     => 'Проект',
                    'value'     => function($data){
                        return Html::a($data->project->name, ['project/view', 'id' => $data->project->id], ['target' => '_blank']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'title',
                    'label'     => 'Задача',
                    'value'     => function($data){
                        return Html::a($data->title, ['task/view', 'id' => $data->id], ['target' => '_blank']);
                    },
                    'format' => 'raw',
                ],
            ],

        ]);
        ?>
    </div>
</div>


