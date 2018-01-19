<?php

use common\models\entities\TaskEntity;
use yii\helpers\Html;
use common\components\dataproviders\EntityDataProvider;
use common\models\entities\CommentEntity;
use yii\widgets\ActiveForm;
use frontend\models\comment\CommentModel;
use frontend\assets\CommentLikeAssset;
use frontend\assets\CommentReplyAsset;
use frontend\assets\TaskLikeAsset;
use yii\widgets\LinkPager;

CommentLikeAssset::register($this);
TaskLikeAsset::register($this);
CommentReplyAsset::register($this);

/**
 * @var TaskEntity $task
 * @var EntityDataProvider $dataProvider
 * @var CommentModel $model
 */

$comments = $dataProvider->getModels();

$pagination = $dataProvider->getPagination(); //пагинатор

$increment = $pagination->pageSize * $pagination->page;  //приращение номера комментария

$counter = 1; //счетчик для номера комментария

?>
    <div class="row">

        <div class="col-md-8">

            <div class="task"
                 data-task-id="<?= $task->getId() ?>"
                 data-current-user-liked-task="<?= $task->getCurrentUserLikedIt() ?>"
                 data-current-user-disliked-task="<?= $task->getCurrentUserDislikedIt() ?>">

                <h2 class="no-margin-top"><?= $task->getTitle() ?></h2>

                <p><?= $task->getContent() ?></p>

                <div class="footer-comment">
                    <span class="vote-up" title="Нравится">
                        <i class="glyphicon glyphicon-thumbs-up"><?= $task->getAmountLikes() ?></i>
                    </span>
                    <span class="vote-down" title="Не нравится">
                        <i class="glyphicon glyphicon-thumbs-down"><?= $task->getAmountDislikes() ?></i>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="task-images-block">
                        <?php foreach ($task->getImagesToTask() as $image) : ?>

                            <div>
                                <?php
                                    $img = Html::img($image->getWebAlias());
                                    echo Html::a($img, ['task/download', 'id' => $image->getId()], ['target' => '_blank']);
                                ?>
                            </div>

                        <?php endforeach; ?>
                    </div>

                    <div class="task-files-block">

                        <?php foreach ($task->getFilesToTask() as $file) : ?>

                            <div>
                                <?= Html::a($file->getOriginalName(), ['task/download', 'id' => $file->getId()], ['target' => '_blank']) ?>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <table class="table">
                <tbody>
                <tr>
                    <td>Проект</td>

                    <td> <?= Html::a($task->getProject()->getName(), ['project/view', 'id' => $task->getProject()->getId()]) ?> </td>
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

        <div class="col-md-8 ">

            <h4><a name="write-comment">Оставить комментарий</a></h4>

            <br>

            <?php
            $form = ActiveForm::begin([
                    'id' => 'comment-form'
            ]);
            ?>

                <?= $form->field($model, 'content')->textarea(['rows' => 2]) ?>

                <?= $form->field($model, 'parentId')->hiddenInput(['id' => 'comment-form-parent-id'])->label(false) ?>

                <div class="comment-form-parent-information"><p>Ответ на: <span class="comment-parent-number"></span></p>
                    <i class="glyphicon glyphicon-remove comment-form-delete-parent"></i>
                </div>

                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="comments">

                <h4 class="comment-amount">Комментарии (<?= $dataProvider->getTotalCount() ?>)</h4>

                <?php
                /**
                 * @var CommentEntity $comment
                 */
                foreach ($comments as $comment):
                ?>

                    <div class="comment-block <?php if($comment->isOwn()) { echo 'own-comment'; } ?>">

                        <div class="comment"
                             data-comment-id="<?= $comment->getId() ?>"
                             data-current-user-liked-it="<?= $comment->getCurrentUserLikedIt() ?>"
                             data-current-user-disliked-it="<?= $comment->getCurrentUserDislikedIt() ?>">

                            <div class="media-left">
                                <?php $img = Html::img($comment->getUser()->getAvatarAlias(), ['class' => 'comment-avatar']) ?>
                                <?= Html::a($img, ['/profile/view', 'id' => $comment->getUser()->getId()]) ?>
                            </div>
                            <div class="media-right">
                                <div class="comment-title">
                                    <h5 class="comment-fio no-margin-top">
                                        <?= Html::a($comment->getUser()->getUsername(), ['/profile/view', 'id' => $comment->getUser()->getId()], ['name' => $comment->getId()]) ?>
                                    </h5>
                                    <p class="comment-number"><?php echo '#' . ($counter + $increment); $counter++; ?></p>
                                </div>

                                <div class="comment-content">
                                    <?php if($comment->getParentId() !== null): ?>

                                        <?php $parent = $comment->getParent(); ?>

                                        <div class="comment-parent">
                                            <div class="comment-parent-username">
                                                <?= Html::a($parent->getUser()->getUsername(), ['/profile/view', 'id' => $parent->getUser()->getId()]) ?>
                                            </div>
                                            <div class="comment-parent-content">>> <?= $parent->getContent() ?> </div>
                                        </div>
                                    <?php endif; ?>
                                    <p><?= $comment->getContent(); ?></p>
                                </div>


                                <div class="footer-comment">
                                    <div class="footer-comment-left">
                                        <span class="comment-date"><?= $comment->getDate() ?></span>
                                        <a href="#write-comment" class="comment-reply">Ответить</a>
                                    </div>

                                    <div class="footer-comment-right">
                                        <span class="vote-up" title="Нравится">
                                            <i class="glyphicon glyphicon-thumbs-up"><?= $comment->getLikesAmount() ?></i>
                                        </span>
                                        <span class="vote-down" title="Нравится">
                                            <i class="glyphicon glyphicon-thumbs-down"><?= $comment->getDislikesAmount() ?></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    endforeach;
                    ?>
                <?php
                // display pagination
                echo LinkPager::widget([
                    'pagination' => $dataProvider->getPagination(),
                ]);
                ?>
            </div>
        </div>
    </div>



