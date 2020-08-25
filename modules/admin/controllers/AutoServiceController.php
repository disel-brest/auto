<?php

namespace app\modules\admin\controllers;

use app\board\forms\manage\AutoService\AutoServiceCreateForm;
use app\board\forms\manage\AutoService\AutoServiceEditForm;
use app\board\forms\manage\AutoService\PhotosForm;
use app\board\services\autoService\AutoServiceManageService;
use Yii;
use app\board\entities\AutoService;
use app\modules\admin\forms\AutoServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AutoServiceController implements the CRUD actions for AutoService model.
 */
class AutoServiceController extends Controller
{
    private $service;

    public function __construct($id, $module, AutoServiceManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-photo' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AutoService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AutoServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AutoService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $autoService = $this->findModel($id);

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($autoService->id, $photosForm);
                return $this->redirect(['view', 'id' => $autoService->id, '#' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'model' => $autoService,
            'photosForm' => $photosForm,
        ]);
    }

    /**
     * Creates a new AutoService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new AutoServiceCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->create($form);
                Yii::$app->session->setFlash("success", "Сервис добавлен");
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing AutoService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $autoService = $this->findModel($id);

        $form = new AutoServiceEditForm($autoService);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($autoService->id, $form);
                return $this->redirect(['view', 'id' => $autoService->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($autoService->id, $photosForm);
                return $this->redirect(['view', 'id' => $autoService->id, '#' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'autoService' => $autoService,
            'photosForm' => $photosForm,
        ]);
    }

    /**
     * Deletes an existing AutoService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeletePhoto($id, $photo_id)
    {
        try {
            $this->service->removePhoto($id, $photo_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    public function actionMove()
    {
        $serviceID = Yii::$app->request->get('id');
        $workID = Yii::$app->request->get('work_id');
        $direction = Yii::$app->request->get('to');

        try {
            $this->service->move($direction, $serviceID, $workID);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $serviceID, '#' => 'positions']);
    }

    /**
     * Finds the AutoService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AutoService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AutoService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
