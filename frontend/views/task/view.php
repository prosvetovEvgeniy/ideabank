<?php

use common\models\entities\TaskEntity;
use yii\helpers\Html;

/**
 * @var TaskEntity $task
 */
?>


<div class="row">

    <div class="col-md-8">

        <h2 class="no-margin-top"><?= $task->getTitle() ?></h2>

        <p><?= $task->getContent() ?></p>

    </div>

    <div class="col-md-4">
        <table class="table">
            <tbody>
            <tr>
                <td>Проект</td>

                <td> <?= Html::a($task->getProject()->getName(), ['project/view', 'projectName' => $task->getProject()->getName()]) ?> </td>
            </tr>
            <tr>
                <td>Создал</td>
                <td> <?= $task->getAuthor()->getUsername() ?> </td>
            </tr>
            <tr>
                <td>Дата создания</td>
                <td> <?= $task->getCreatedDate() ?> </td>
            </tr>
            <tr>
                <td>Дата обновления</td>
                <td> <?= $task->getUpdatedDate() ?> </td>
            </tr>
            <tr>
                <td>Статус</td>
                <td> <?= $task->getStatusAsText() ?> </td>
            </tr>

            <?php $status = $task->getStatus(); ?>

            <?php if($status === TaskEntity::STATUS_ON_CONSIDERATION || $status === TaskEntity::STATUS_IN_PROGRESS): ?>

                <tr>
                    <td>Будет завершена</td>
                    <td> <?= $task->getPlannedEndDate() ?> </td>
                </tr>

            <?php elseif ($status === TaskEntity::STATUS_COMPLETED): ?>

                <tr>
                    <td>Дата завершения</td>
                    <td> <?= $task->getEndDate() ?> </td>
                </tr>

            <?php elseif ($status === TaskEntity::STATUS_MERGED): ?>

                <?php
                    $parentTask = $task->getParent();
                ?>

                <tr>
                    <td>Задача</td>
                    <td> <?= Html::a($parentTask->getTitle(), ['task/view', 'taskId' => $parentTask->getId()]) ?> </td>
                </tr>

            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>