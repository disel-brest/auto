<?php

namespace app\modules\admin\controllers;

use app\board\entities\AutoServiceCategory;
use app\board\forms\manage\AutoServiceCategoryForm;
use app\board\forms\manage\AutoServiceWorkForm;
use app\board\services\autoService\AutoServiceWorkManageService;
use Yii;
use app\board\entities\AutoServiceWork;
use app\modules\admin\forms\AutoServiceWorkSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AutoServiceWorkController implements the CRUD actions for AutoServiceWork model.
 */
class AutoServiceWorkController extends Controller
{
    private $service;

    public function __construct($id, $module, AutoServiceWorkManageService $service, $config = [])
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
                ],
            ],
        ];
    }

    /**
     * Lists all AutoServiceWork models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AutoServiceWorkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AutoServiceWork model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AutoServiceWork model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new AutoServiceWorkForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $autoServiceWork = $this->service->create($form);
                Yii::$app->session->setFlash("success", "Вид работ добавлен");
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
     * Updates an existing AutoServiceWork model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $form = new AutoServiceWorkForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $autoServiceWork = $this->service->update($id, $form);
                Yii::$app->session->setFlash("success", "Изменения сохранены");
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    /**
     * Deletes an existing AutoServiceWork model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCategories()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AutoServiceCategory::find(),
        ]);

        return $this->render('categories', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateCategory()
    {
        $form = new AutoServiceCategoryForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $category = $this->service->createCategory($form);
                Yii::$app->session->setFlash("success", "Категория создана");
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create-category', [
            'model' => $form,
        ]);
    }

    public function actionDeleteCategory($id)
    {
        $this->findCategory($id)->delete();
        Yii::$app->session->setFlash('success', 'Категория удалена');

        return $this->redirect(['index']);
    }

    public function actionUpdateCategory($id)
    {
        $form = new AutoServiceCategoryForm($this->findCategory($id));

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->updateCategory($id, $form);
                Yii::$app->session->setFlash("success", "Изменения сохранены");
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update-category', [
            'model' => $form,
        ]);
    }

    /**
     * Finds the AutoServiceWork model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AutoServiceWork the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AutoServiceWork::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findCategory($id)
    {
        if (($model = AutoServiceCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
