<?php

namespace frontend\controllers;


use common\models\repositories\TaskFileRepository;
use frontend\models\taskfile\TaskFileDeleteModel;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;


class TaskFileController extends Controller
{
    public function actionDownload(int $id)
    {
        $file = TaskFileRepository::instance()->findOne(['id' => $id]);

        if(!$file)
        {
            throw new NotFoundHttpException();
        }

        $headers = Yii::$app->response->getHeaders();

        $headers->set('Content-Type', $file->getMimeType());

        if($file->isImage())
        {
            $headers->set('Content-Disposition', 'inline');
        }
        else
        {
            $headers->set('ContentDisposition', 'attachment');
        }

        return Yii::$app->response->sendFile($file->getWebRootAlias(), $file->getOriginalName());
    }


    //###################### AJAX ACTIONS ######################


    public function actionDelete()
    {
        $model = new TaskFileDeleteModel();

        if(!$model->load(Yii::$app->request->post()) || !$model->delete())
        {
            throw new BadRequestHttpException();
        }
    }
}