<?php

use yii\helpers\Html;
use common\components\helpers\LinkHelper;
use common\models\searchmodels\task\TaskSearchForm;

/**
 * @var \common\models\entities\ProjectEntity $project
 */

$this->title = $project->getName(true);
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="center-block">
            <div><h2><?= $project->getName(true) ?></h2></div>
            <div><?= $project->getDescription(true) ?></div>
            <div><?= Html::a('Количество задач : ' . $project->getAmountTasks(), LinkHelper::getLinkOnActionTaskIndex($project, TaskSearchForm::STATUS_ALL)) ?></div>

            <?php if(Yii::$app->user->isManager($project->getId())): ?>
                <div><?= Html::a('Участники проекта', ['/project/participants', 'ParticipantSearchForm[projectId]' => $project->getId()]) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>




