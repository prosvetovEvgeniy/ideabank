<?php

namespace frontend\controllers;


use frontend\models\CommentVoteModel;
use yii\web\Controller;
use Yii;
use yii\web\BadRequestHttpException;
use common\models\repositories\CommentLikeRepository;
use common\models\entities\CommentLikeEntity;
use yii\db\Exception;

class CommentController extends Controller
{
    /**
     * @throws BadRequestHttpException
     */
    public function actionAddvote()
    {
        $commentId = Yii::$app->request->post('commentId');

        $like =  (Yii::$app->request->post('like') === 'true') ? true : false;

        if(Yii::$app->user->identity === null)
        {
            throw new BadRequestHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        $model = new CommentVoteModel();

        $postData = [
            $model->formName() => [
                'userId' => $userId,
                'commentId' => $commentId,
                'liked' => $like
            ]
        ];

        $model->load($postData);

        if(!$model->validate() || $model->recordExist())
        {
            throw new BadRequestHttpException();
        }

        $commentLike = new CommentLikeEntity($commentId, $userId, $like);

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
            throw new BadRequestHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        try
        {
            $commentLike = CommentLikeRepository::instance()->findOne([
                'comment_id' => $commentId,
                'user_id'    => $userId,
            ]);

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
            throw new BadRequestHttpException();
        }

        $userId = Yii::$app->user->identity->getEntity()->getId();

        try
        {
            $commentLike = CommentLikeRepository::instance()->findOne([
                'comment_id' => $commentId,
                'user_id'    => $userId,
            ]);

            ($commentLike->getLiked() === true) ? $commentLike->dislike() : $commentLike->like();

            CommentLikeRepository::instance()->update($commentLike);
        }
        catch (Exception $e)
        {
            throw new BadRequestHttpException();
        }
    }
}