<?php

use common\components\dataproviders\EntityDataProvider;
use common\models\entities\NoticeEntity;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var EntityDataProvider $dataProvider
 */
?>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="center-block">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout'=>"{items}\n{pager}",
                'options' => ['class' => 'text-center'],
                'headerRowOptions' => ['class' => 'center-header-text'],
                'columns' =>[
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'sender',
                        'header' => 'Упомянул',
                        'value' => function (NoticeEntity $notice) {
                            if(Yii::$app->user->identity->getUser()->getid() === $notice->getSenderId())
                            {
                                return Html::a($notice->getSender()->getUsername(), ['/profile/my-tasks']);
                            }
                            return Html::a($notice->getSender()->getUsername(), ['/profile/view', 'id' => $notice->getSenderId()]);
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'content',
                        'contentOptions' => ['class' => 'text-left'],
                        'header' => 'Содержание',
                        'value' => function(NoticeEntity $notice) {
                            return Html::a($notice->getContent(), $notice->getLink(), ['class' => 'text-hidden', 'target' => '_blank']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'createdAtDate',
                        'header' => 'Дата',
                        'value' => function(NoticeEntity $notice) {
                            return Html::tag('code',$notice->getCreatedAtDate());
                        },
                        'format' => 'html'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function($url, NoticeEntity $notice){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $notice->getId()]);
                            },
                        ],
                    ]
                ]
            ])
            ?>
        </div>
    </div>
</div>

