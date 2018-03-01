<?php

use common\models\entities\ProjectEntity;
use yii\widgets\ActiveForm;
use common\models\searchmodels\participant\ParticipantSearchForm;
use common\components\helpers\ProjectHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\entities\AuthAssignmentEntity;
use common\models\entities\ParticipantEntity;
use common\components\dataproviders\EntityDataProvider;
use common\components\widgets\RoleViewWidget;
use common\components\widgets\ParticipantActionWidget;

/**
 * @var ProjectEntity $project
 * @var EntityDataProvider $dataProvider;
 * @var ParticipantSearchForm $model
 */

?>

<?php $a = '5'; ?>

<div class="row">
    <div class="col-lg-3">
        <?php
        $form = ActiveForm::begin([
            'action' => ['participants'],
            'method' => 'get',
            'options' => [
                'class' => 'form-vertical',
            ],
        ]);
        ?>

        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'firstName')->textInput() ?>

        <?= $form->field($model, 'secondName')->textInput() ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'phone')->textInput() ?>

        <?= $form->field($model, 'role')->dropDownList(AuthAssignmentEntity::LIST_ROLES, ['prompt' => 'Все']) ?>

        <?= $form->field($model, 'projectId')->dropDownList(ProjectHelper::getProjectForManagerItems()) ?>

        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-9">

        <h4>Найдено: <?= $dataProvider->getTotalCount() ?></h4>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{items}\n{pager}",
            'options' => ['class' => 'text-center'],
            'headerRowOptions' => ['class' => 'center-header-text'],
            'columns' =>[
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'username',
                    'header' => 'Логин',
                    'value' => function(ParticipantEntity $participant) {
                        return Html::a($participant->getUser()->getUsername(true), ['/profile/view', 'id' => $participant->getUser()->getId()]);
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Статус',
                    'value' => function(ParticipantEntity $participant) {
                        return RoleViewWidget::widget(['participant' => $participant]);
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'approvedAt',
                    'header' => 'Дата добавления',
                    'value' => function(ParticipantEntity $participant) {
                        return Html::tag('code', $participant->getApprovedAtDate());
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => '',
                    'header' => '',
                    'value' => function(ParticipantEntity $participant) {
                        return ParticipantActionWidget::widget([
                            'participant' => $participant
                        ]);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => '',
                    'header' => '',
                    'value' => function(ParticipantEntity $participant){
                        $viewTag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-user participant-action-tag', 'title' => 'Просмотр']);

                        if (!$participant->hasOnConsiderationRole()){
                            return Html::a($viewTag, ['/project/participant-view', 'id' => $participant->getId()]);
                        }

                        return '';
                    },
                    'format' => 'raw'
                ]
            ]
        ])
        ?>
    </div>
</div>