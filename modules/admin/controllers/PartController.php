<?php

namespace app\modules\admin\controllers;

use app\modules\admin\forms\NewUserForm;
use app\modules\main\forms\AddPartForm;
use app\modules\main\forms\PartForm;
use Yii;
use app\modules\main\models\AdPart;
use app\modules\admin\models\search\PartSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartController implements the CRUD actions for AdPart model.
 */
class PartController extends Controller
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
     * Lists all AdPart models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdPart model.
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
     * Creates a new AdPart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userForm = new NewUserForm();
        $partsForm = new AddPartForm();
        $partsForm->scenario = AddPartForm::SCENARIO_ADMIN_CREATE;

        if ($partsForm->load(Yii::$app->request->post()) && $userForm->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user = $userForm->create()) {
                    $partsForm->setUser($user);
                    if ($partsForm->add()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash("success", "Объявления добавлены");
                        $userForm = new NewUserForm();
                        $partsForm = new AddPartForm();
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

            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'userForm' => $userForm,
            'partsForm' => $partsForm,
        ]);
    }

    /**
     * Updates an existing AdPart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $part = $this->findModel($id);
        $form = new PartForm($part);

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash("success", "Данные сохранены");
            return $this->redirect(['view', 'id' => $part->id]);
        } else {
            return $this->render('update', [
                'part' => $part,
                'model' => $form,
            ]);
        }
    }

    /**
     * Deletes an existing AdPart model.
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
     * Finds the AdPart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdPart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdPart::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
