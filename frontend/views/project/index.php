<?php

use yii\helpers\Html;
use common\components\helpers\ParticipantHelper;
use common\components\helpers\ProjectHelper;
use common\models\activerecords\Participant;

/**
 * @var \common\models\activerecords\Participant $participants
 */
?>

<?php


?>
<div class="row">

    <?php foreach ($participants as $participant) : ?>

        <div class="col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <?= $participant->project->name ?>

                    <?php
                        $role = ParticipantHelper::getRoleAsString($participant);
                    ?>

                    <?php if($role === Participant::USER_ROLE && $participant->approved) : ?>

                        <span class="label label-success">Участник</span>

                    <?php elseif ($role === Participant::MANAGER_ROLE && $participant->approved) : ?>

                        <span class="label label-warning">Менеджер</span>

                    <?php elseif ($role === Participant::PROJECT_DIRECTOR_ROLE && $participant->approved) : ?>

                        <span class="label label-primary">Директор</span>

                    <?php endif; ?>

                </div>
                <div class="panel-body">
                    <p><?= Html::a('Количество задач : ' . ProjectHelper::getTasksCount($participant->project), ['#'], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Завершенные', ['#'], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Не завершенные', ['#'], ['target' => '_blank']) ?></p>
                    <p><?= Html::a('Мои задачи', ['#'], ['target' => '_blank']) ?></p>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>
