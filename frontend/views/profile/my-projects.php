<?php

use common\components\widgets\ProfileMenuWidget;
use frontend\assets\SubMenuAsset;
use common\models\entities\ParticipantEntity;
use common\components\widgets\RoleViewWidget;
use yii\helpers\Html;
use frontend\assets\ProfileProjectsAsset;
use yii\grid\GridView;
use common\components\dataproviders\EntityDataProvider;

SubMenuAsset::register($this);
ProfileProjectsAsset::register($this);

/**
 * @var EntityDataProvider $dataProvider;
 */

$this->title = 'Мои проекты';
?>

<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3">
        <?= ProfileMenuWidget::widget() ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-9">
        <div class="dialogs-block">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout'=>"{items}\n{pager}",
                'caption' => 'Текущие',
                'captionOptions' => ['class' => 'header'],
                'options' => ['class' => 'text-center'],
                'headerRowOptions' => ['class' => 'center-header-text'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'company',
                        'header' => 'Компания',
                        'value' => function(ParticipantEntity $participant) {
                            return $participant->getCompany()->getName(true);
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'project',
                        'header' => 'Проект',
                        'value' => function(ParticipantEntity $participant) {
                            return $participant->getProject()->getName(true);
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'status',
                        'header' => 'Статус',
                        'value' => function(ParticipantEntity $participant) {
                            return RoleViewWidget::widget(['participant' => $participant]);
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'created_at_date',
                        'header' => 'Дата вступления',
                        'value' => function(ParticipantEntity $participant) {
                            return Html::tag('code', $participant->getApprovedAtDate());
                        },
                        'format' => 'html',
                    ],
                    [
                        'header' => '',
                        'value' => function(ParticipantEntity $participant){
                            if ($participant->isProjectDirector()) {
                                return Html::a('Закрыть', '#', [
                                    'data'  => ['participant-id' => $participant->getId()]
                                ]);
                            } else {
                                return Html::a('Покинуть', '/participant/delete', [
                                    'class' => 'leave-project',
                                    'data'  => ['participant-id' => $participant->getId()]
                                ]);
                            }
                        },
                        'format' => 'raw',
                    ],
                ]
            ]);
            ?>

        </div>
    </div>
</div>