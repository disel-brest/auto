<?php

namespace app\modules\admin\controllers;

use app\modules\admin\forms\NewUserForm;
use app\modules\main\forms\AddTireForm;
use Yii;
use app\modules\main\models\AdTire;
use app\modules\admin\models\search\TireSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TireController implements the CRUD actions for AdTire model.
 */
class TireController extends Controller
{
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
     * Lists all AdTire models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TireSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdTire model.
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
     * Creates a new AdTire model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userForm = new NewUserForm();
        $tireForm = new AddTireForm();
        $tireForm->scenario = AddTireForm::SCENARIO_ADMIN_CREATE;

        if ($userForm->load(Yii::$app->request->post()) && $tireForm->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user = $userForm->create()) {
                    $tireForm->setUser($user);
                    if ($tireForm->add()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash("success", "Объявление добавлено");
                        $userForm = new NewUserForm();
                        $tireForm = new AddTireForm();
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("danger", $e->getMessage());
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'userForm' => $userForm,
            'tireForm' => $tireForm,
        ]);
    }

    /**
     * Updates an existing AdTire model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return $this->redirect(['/main/tires/edit', 'id' => $id, 'from_cp' => 1]);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdTire model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdTire model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdTire the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdTire::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
