<?php

namespace frontend\controllers;


use frontend\models\CommentVoteModel;
use yii\web\Controller;
use Yii;
use yii\web\BadRequestHttpException;
use common\models\repositories\CommentLikeRepository;
use common\models\entities\CommentLikeEntity;
use yii\db\Exception;
use yii\web\UnauthorizedHttpException;

class CommentLikeController extends Controller
{
    /**
     * @throws BadRequestHttpException
     */
    public function actionAddvote()
    {
        if(Yii::$app->user->identity === null)
        {
            throw new UnauthorizedHttpException();
        }

        $model = new CommentVoteModel();
        $model->userId = Yii::$app->user->identity->getEntity()->getId();

        $model->load(Yii::$app->request->post());

        if(!$model->validate() || $model->recordExist())
        {
            throw new BadRequestHttpException();
        }

        $commentLike = new CommentLikeEntity($model->commentId, $model->userId, $model->liked);

        try
        {
            CommentLikeRepository::instance()->add($commentLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionDeletevote()
    {
        $commentId = Yii::$app->request->post('commentId');

        if(Yii::$app->user->identity === null)
        {
            throw new UnauthorizedHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        $commentLike = CommentLikeRepository::instance()->findOne([
            'comment_id' => $commentId,
            'user_id'    => $userId,
        ]);

        if(!$commentLike)
        {
            throw new BadRequestHttpException();
        }

        try
        {
            CommentLikeRepository::instance()->delete($commentLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionReversevote()
    {
        $commentId = Yii::$app->request->post('commentId');

        if(Yii::$app->user->identity === null)
        {
            throw new UnauthorizedHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        $commentLike = CommentLikeRepository::instance()->findOne([
            'comment_id' => $commentId,
            'user_id'    => $userId,
        ]);

        if(!$commentLike)
        {
            throw new BadRequestHttpException();
        }

        ($commentLike->getLiked() === true) ? $commentLike->dislike() : $commentLike->like();

        try
        {
            CommentLikeRepository::instance()->update($commentLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }
}