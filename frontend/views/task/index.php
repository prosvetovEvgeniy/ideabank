<?php

use common\components\dataproviders\EntityDataProvider;

/** @var EntityDataProvider $dataProvider */
/** @var \common\models\searchmodels\TaskEntitySearch $searchModel */
?>
<h1>This is task page</h1>

<br>
<br>

<div class="row">
    <div class="col-md-2">
        <h3>LEFT SIDE BAR</h3>
    </div>

    <div class="col-md-10">

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?=
        \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{items}\n{pager}",
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'header' => 'Заголовок',
                    'value' => function($entity) { return $entity->getTitle(); }
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Статус',
                    'value' => function($entity) { return $entity->getStatusAsText(); },
                ],
                [
                    'attribute' => 'creationDate',
                    'header' => 'Дата создания',
                    'value' => function($entity) { return $entity->getCreatedDate(); }
                ],
                [
                    'attribute' => 'plannedEndDate',
                    'header' => 'Планируемая дата завершения',
                    'value' => function($entity) { return $entity->getPlannedEndDate(); }
                ],
            ]
        ]);
        ?>
    </div>
</div>


