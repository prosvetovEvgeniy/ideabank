<?php

use yii\helpers\Html;

/**
 * @var \common\models\entities\ParticipantEntity[] $participants
 */
?>

<?php


?>
<div class="row">

    <?php foreach ($participants as $participant) : ?>

        <?php
            $project = $participant->getProject();
        ?>

        <div class="col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">

                    <?= $project->getName() ?>

                </div>

                <div class="panel-body">
                    <p><?= Html::a('Количество задач : ' . $project->getAmountTasks() , ['#'], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Завершенные : ' . $project->getAmountCompletedTasks(), ['#'], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Не завершенные : ' . $project->getAmountNotCompletedTasks(), ['#'], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Мои задачи : ' . $project->getAmountTasksByUser($participant->getUser()), ['#'], ['target' => '_blank']) ?></p>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>
