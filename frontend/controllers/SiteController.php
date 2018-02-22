<?php
namespace frontend\controllers;

use common\components\dataproviders\EntityDataProvider;
use common\models\entities\TaskEntity;
use common\models\repositories\task\ActualTasksRepository;
use common\models\repositories\task\TaskRepository;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\forms\LoginForm;
use frontend\models\authentication\PasswordResetRequestForm;
use frontend\models\authentication\ResetPasswordForm;
use frontend\models\authentication\SignupForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $actualTasksDataProvider = new EntityDataProvider([
            'condition' => [],
            'repositoryInstance' => ActualTasksRepository::instance(),
            'pagination' => [
                'pageSizeLimit' => [TaskEntity::ACTUAL_TASKS_COUNT, TaskEntity::ACTUAL_TASKS_COUNT]
            ],
            'with' => ['project']
        ]);

        $lastTasksDataProvider = new EntityDataProvider([
            'condition' => [
                'and',
                ['visibility_area' => TaskEntity::VISIBILITY_AREA_ALL],
                ['deleted' => false]
            ],
            'repositoryInstance' => TaskRepository::instance(),
            'pagination' => [
                'pageSizeLimit' => [TaskEntity::ACTUAL_TASKS_COUNT, TaskEntity::ACTUAL_TASKS_COUNT]
            ],
            'orderBy' => 'created_at DESC',
            'with' => ['project']
        ]);

        return $this->render('index', [
            'actualTasksDataProvider' => $actualTasksDataProvider,
            'lastTasksDataProvider' => $lastTasksDataProvider
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignupUser()
    {
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_USER_SIGN_UP;

        if ($model->load(Yii::$app->request->post()) && $model->signUpUser()) {
            if(Yii::$app->getUser()->login($model->getUser())) {
                return $this->goHome();
            }
        }

        return $this->render('signupUser', ['model' => $model]);
    }

    public function actionSignupDirector()
    {
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_DIRECTOR_SIGN_UP;

        if($model->load(Yii::$app->request->post()) && $model->signUpDirector()) {
            if(Yii::$app->getUser()->login($model->getUser())) {
                return $this->goHome();
            }
        }

        return $this->render('signupDirector', ['model' => $model]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свой email и следуйте дальнейшим инструкциям.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Мы не можем восстановить пароль для предоставленного адреса email.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
