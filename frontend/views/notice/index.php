<?php

use common\components\dataproviders\EntityDataProvider;
use common\models\entities\NoticeEntity;
use yii\grid\GridView;
use yii\helpers\Html;
use frontend\assets\DeleteNoticeAsset;

/**
 * @var EntityDataProvider $dataProvider
 */

DeleteNoticeAsset::register($this);

$this->title = 'Упоминания';
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
                                return Html::a($notice->getSender()->getUsername(true), ['/profile/my-tasks']);
                            }
                            return Html::a($notice->getSender()->getUsername(true), ['/profile/view', 'id' => $notice->getSenderId()]);
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'content',
                        'contentOptions' => ['class' => 'text-left'],
                        'header' => 'Содержание',
                        'value' => function(NoticeEntity $notice) {
                            return Html::a($notice->getContent(true), $notice->getLink(), ['class' => 'text-hidden']);
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
                        'attribute' => '',
                        'header' => '',
                        'value' => function(NoticeEntity $notice) {
                            return Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash notice-delete', 'data' => ['id' => $notice->getId()]]);
                        },
                        'format' => 'raw'
                    ]
                ]
            ])
            ?>
        </div>
    </div>
</div>

