<?php

namespace app\modules\user\controllers;

use app\modules\user\forms\EmailConfirmForm;
use app\modules\user\forms\LoginForm;
use app\modules\user\forms\PasswordResetRequestForm;
use app\modules\user\forms\ResetPasswordForm;
use app\modules\user\forms\SignupForm;
use app\modules\user\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'login', 'password-reset-request'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login', 'password-reset-request'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'signup' => ['post'],
                    'login' => ['post'],
                    'password-reset-request' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                //'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return array|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest || !Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                return ['result' => 'success'];
            }
        }

        return ['result' => false, 'errors' => $model->getErrors()];
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Регистрация пользователя
     * @return string|Response
     */
    public function actionSignup()
    {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return ['result' => 'success'];
            }
        }

        return ['result' => false, 'errors' => $model->getErrors()];
    }

    public function actionEmailConfirm($token)
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $user = User::findByEmailConfirmToken($token);
        if ($model->confirmEmail()) {
            //Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш Email успешно подтверждён.');
            Yii::$app->user->login($user, 3600);
            return Yii::$app->response->redirect(['/user/cabinet']);
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения Email.');
        }

        return $this->goHome();
    }

    public function actionPasswordResetRequest()
    {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return ['result' => 'success'];
            } else {
                return ['result' => false, 'errors' => $model->getErrors()];
            }
        }

        return ['result' => false];
        /*return $this->render('password-reset-request', [
            'model' => $model,
        ]);*/
    }

    public function actionPasswordReset($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');

            return $this->goHome();
        }

        return $this->render('password-reset', [
            'model' => $model,
        ]);
    }
}
