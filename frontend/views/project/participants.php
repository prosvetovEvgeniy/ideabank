<?php

use common\models\entities\ProjectEntity;
use yii\widgets\ActiveForm;
use common\models\searchmodels\project\ParticipantSearchForm;
use common\components\helpers\ProjectHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\entities\ParticipantEntity;
use common\components\dataproviders\EntityDataProvider;
use common\components\widgets\RoleViewWidget;
use common\models\entities\AuthAssignmentEntity;
use frontend\assets\ProjectParticipantsAsset;

/**
 * @var ProjectEntity $project
 * @var EntityDataProvider $dataProvider;
 * @var ParticipantSearchForm $model
 */

ProjectParticipantsAsset::register($this);

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

        <?= $form->field($model, 'role')->dropDownList(ParticipantEntity::LIST_ROLES, ['prompt' => 'Все']) ?>

        <?= $form->field($model, 'projectId')->dropDownList(ProjectHelper::getProjectForManagerItems()) ?>

        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-9">
        <h4>Найдено: <?= $dataProvider->getTotalCount() ?></h4>

        <?php
        $viewTag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-user participant-action-tag', 'title' => 'Просмотр']);
        $blockTag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-ban-circle participant-action-tag block-tag', 'title' => 'Заблокировать']);
        $addTag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-ok participant-action-tag add-tag', 'title' => 'Добавить']);
        $unBlockTag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-ok-circle participant-action-tag un-block-tag', 'title' => 'Разблокировать']);
        $cancelTag = Html::tag('i', '', ['class' => 'glyphicon glyphicon-remove participant-action-tag cancel-tag', 'title' => 'Не добавлять']);
        ?>
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
                    'value' => function(ParticipantEntity $participant) use ($addTag, $blockTag, $unBlockTag, $cancelTag){
                        if($participant->getRoleName() === ParticipantEntity::ROLE_BLOCKED){
                            return Html::tag('div', $unBlockTag, ['data' => ['participant-id' => $participant->getId()]]);
                        } elseif($participant->getRoleName() === ParticipantEntity::ROLE_ON_CONSIDERATION) {
                            return Html::tag('div', $addTag . $cancelTag, ['data' => ['participant-id' => $participant->getId()]]);
                        } elseif($participant->getRoleName() === AuthAssignmentEntity::ROLE_USER) {
                            return Html::tag('div', $blockTag, ['data' => ['participant-id' => $participant->getId()]]);
                        }

                        return '';
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => '',
                    'header' => '',
                    'value' => function(ParticipantEntity $participant) use ($viewTag){
                        if ($participant->getRoleName() !== ParticipantEntity::ROLE_ON_CONSIDERATION){
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