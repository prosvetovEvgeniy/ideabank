<?php

use common\models\searchmodels\TaskEntitySearch;
use yii\helpers\Html;

/**
 * @var \common\models\entities\ProjectEntity $project
 */

$this->title = $project->getName();
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="center-block">
            <div><h2><?= $project->getName() ?></h2></div>
            <div><?= $project->getDescription() ?></div>
            <div><?= Html::a('Количество задач : ' . $project->getAmountTasks() , ['task/index', 'TaskEntitySearch[projectId]' => $project->getId(), 'TaskEntitySearch[status]' => TaskEntitySearch::STATUS_ALL], ['target' => '_blank']) ?></div>
        </div>
    </div>
</div>




