<?php

use yii\helpers\Html;

/** @var \common\models\entities\ProjectEntity $project */
?>


<h2><?= Html::encode($project->getName()) ?></h2>

<div class="row">
    <div class="col-md-6">
        <p>Описание проекта: ...</p>
    </div>
</div>

<br>

<div class="row">

    <div class="col-md-2">
        <p><?= Html::a('Количество задач : ' . $project->getAmountTasks() , ['task/index'], ['target' => '_blank']) ?></p>
    </div>

    <div class="col-md-2">
        <p><?= Html::a('Создать задачу', ['task/create'], ['target' => '_blank']) ?></p>
    </div>

</div>




