<?php

use common\models\entities\TaskEntity;
use yii\helpers\Html;
use common\components\dataproviders\EntityDataProvider;
use common\models\entities\CommentEntity;

/**
 * @var TaskEntity $task
 * @var EntityDataProvider $dataProvider
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
                    <td><code><?= $task->getCreatedDate() ?></code></td>
                </tr>
                <tr>
                    <td>Дата обновления</td>
                    <td><code><?= $task->getUpdatedDate() ?></code></td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td> <?= $task->getStatusAsText() ?> </td>
                </tr>

                <?php $status = $task->getStatus(); ?>

                <?php if($status === TaskEntity::STATUS_ON_CONSIDERATION || $status === TaskEntity::STATUS_IN_PROGRESS): ?>

                    <tr>
                        <td>Будет завершена</td>
                        <td><code><?= $task->getPlannedEndDate() ?></code></td>
                    </tr>

                <?php elseif ($status === TaskEntity::STATUS_COMPLETED): ?>

                    <tr>
                        <td>Дата завершения</td>
                        <td><code><?= $task->getEndDate() ?></code></td>
                    </tr>

                <?php elseif ($status === TaskEntity::STATUS_MERGED): ?>

                    <?php
                        $parentTask = $task->getParent();
                    ?>

                    <tr>
                        <td>Задача</td>
                        <td> <?= Html::a($task->getParent()->getTitle(), ['task/view', 'taskId' => $task->getParent()->getId()]) ?> </td>
                    </tr>

                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="comments">

                <h4 class="comment-amount">Комментарии (<?= $dataProvider->getTotalCount() ?>)</h4>

                <?php

                $comments = $dataProvider->getModels();

                /**
                 * @var CommentEntity $comment
                 */
                foreach ($comments as $comment):
                ?>

                <div class="comment-block">
                    <div class="comment">
                        <div class="media-left">
                            <a href="#">
                                <img class="comment-avatar" src="/images/pyramid.jpg">
                            </a>
                        </div>
                        <div class="media-right">
                            <h5 class="comment-fio no-margin-top">
                                <a href="#">
                                    <?= $comment->getUser()->getUsername() ?>
                                </a>
                            </h5>
                            <p><?= $comment->getContent(); ?></p>

                            <div class="footer-comment">

                                <div class="footer-comment-left">
                                    <span class="comment-date">11 декабря 2017</span>
                                    <a href="#" class="comment-reply">Ответить</a>
                                </div>

                                <div class="footer-comment-right">
                                    <span class="vote-up" title="Нравится">
                                        <i class="glyphicon glyphicon-thumbs-up"><?= $comment->getAmountLikes() ?></i>
                                    </span>
                                    <span class="vote-down" title="Нравится">
                                        <i class="glyphicon glyphicon-thumbs-down"><?= $comment->getAmountDislikes() ?></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <?php
                endforeach;
                ?>

            </div>
        </div>
    </div>

    <?php
    // display pagination
    echo \yii\widgets\LinkPager::widget([
        'pagination' => $dataProvider->getPagination(),
    ]);
    ?>

</div>