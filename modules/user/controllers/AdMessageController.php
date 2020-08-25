<?php

namespace app\modules\user\controllers;


use app\board\forms\AdMessageCreateForm;
use app\board\repositories\MessageRepository;
use app\board\services\AdMessageService;
use app\rbac\Rbac;
use Yii;
use yii\base\Module;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AdMessageController extends Controller
{
    private $service;
    private $repository;

    public function __construct($id, Module $module, AdMessageService $service, MessageRepository $repository, array $config = [])
    {
        $this->service = $service;
        $this->repository = $repository;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_USER],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'new' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        //Заглушка
        throw new UserException("Сервис сообщений находится в разработке и пока не доступен");

        $dataProvider = $this->repository->getDialogsByUserId(Yii::$app->user->id);
        $messagesDataProvider = $this->repository->getMessageDialogsByUserId(Yii::$app->user->id);
        $messagesCount = $this->repository->getMessagesCountByUserId(Yii::$app->user->id);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'messagesDataProvider' => $messagesDataProvider,
            'messagesCount' => $messagesCount,
        ]);
    }

    public function actionNew()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $adType = Yii::$app->request->post('adType');
        $adId = Yii::$app->request->post('adId');
        $message = Yii::$app->request->post('message');

        try {
            $this->service->create($message, $adId, $adType, Yii::$app->user->id);
            return ['result' => 'success'];
        } catch (\DomainException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function actionView($id)
    {
        $dialog = $this->repository->getDialog($id);
        $form = new AdMessageCreateForm($dialog);

        $this->service->setRead($dialog->id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->send($form);
                return $this->redirect(['view', 'id' => $id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('view', [
            'dialog' => $dialog,
            'model' => $form,
        ]);
    }

    public function actionViewMessage($id)
    {
        $dialog = $this->repository->getMessageDialog($id);
        $this->service->setRead($dialog->id, true);

        return $this->render('view-message', [
            'dialog' => $dialog,
        ]);
    }

    public function actionDelete($id)
    {
        $this->service->delete($id);
        return $this->redirect(['index']);
    }
}