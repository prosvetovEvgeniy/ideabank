<?php

use common\components\dataproviders\EntityDataProvider;
use yii\grid\GridView;
use common\components\widgets\ProjectSearchWidget;
use common\models\entities\ProjectEntity;
use yii\helpers\Html;
use common\models\entities\ParticipantEntity;
use common\models\entities\UserEntity;
use common\models\searchmodels\task\TaskEntitySearch;
use frontend\assets\ProjectJoinAsset;


ProjectJoinAsset::register($this);

/**
 * @var EntityDataProvider $dataProvider
 * @var string $projectName
 * @var UserEntity $user
 */

$this->title = 'Поиск';
?>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="center-block">
            <?= ProjectSearchWidget::widget(['searchValue' => $projectName]) ?>
            <div class="projects-search-grid">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout'=>"{items}\n{pager}",
                    'captionOptions' => ['class' => 'header'],
                    'options' => ['class' => 'text-center'],
                    'headerRowOptions' => ['class' => 'center-header-text'],
                    'columns' =>[
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'name',
                            'header' => 'Название',
                            'value' => function(ProjectEntity $project) {
                                /**
                                 * @var ProjectEntity $project
                                 */
                                return Html::a($project->getName(), ['/project/view/', 'id' => $project->getId()]);
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'companyName',
                            'header' => 'Название компании',
                            'value' => function(ProjectEntity $project) {
                                /**
                                 * @var ProjectEntity $project
                                 */
                                return $project->getCompany()->getName();
                            },
                        ],
                        [
                            'attribute' => 'description',
                            'header' => 'Описание',
                            'value' => function(ProjectEntity $project) {
                                /**
                                 * @var ProjectEntity $project
                                 */
                                return $project->getDescription();
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'created_at',
                            'header' => 'Дата регистрации',
                            'value' => function(ProjectEntity $project) {
                                /**
                                 * @var ProjectEntity $project
                                 */
                                $date = '<code>' . $project->getCreatedAtDate() . '</code>';
                                return $date;
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => '',
                            'header' => '',
                            'value' => function(ProjectEntity $project) {

                                if(Yii::$app->user->isGuest)
                                {
                                    return Html::a('Вступить', '/site/login');
                                }

                                /**
                                 * @var ParticipantEntity $participants
                                 * @var UserEntity        $user
                                 */
                                $participants = $project->getParticipants();
                                $user = Yii::$app->user->identity->getUser();


                                /**
                                 * @var ParticipantEntity $participant
                                 */
                                foreach ($participants as $participant)
                                {
                                    if($participant->getUserId() === $user->getId())
                                    {
                                        if($participant->getApproved() && !$participant->getBlocked())
                                        {
                                            return Html::a('Перейти', ['/task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_ALL]);
                                        }
                                        else if(!$participant->getApproved())
                                        {
                                            return '<code>На рассмотрении </code>';
                                        }
                                        else if($participant->getBlocked())
                                        {
                                            return '<code>Забанен</code>';
                                        }
                                    }
                                }

                                return Html::a('Вступить', '/project/join', ['class' => 'project-join','data' => ['user-id' => $user->getId(), 'project-id' => $project->getId()]]);
                            },
                            'format' => 'raw'
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
