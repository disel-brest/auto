<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\main\models\CarOptions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarOptionsController implements the CRUD actions for CarOptions model.
 */
class CarOptionsController extends Controller
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
     * Lists all CarOptions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $categoryId = (int)Yii::$app->request->post('category_id');
        $categoryId = $categoryId ? $categoryId : null;

        $optionsDataProvider = new ActiveDataProvider([
            'query' => CarOptions::find()
                ->where(['>', 'parent_id', 0])
                ->andFilterWhere([
                    'parent_id' => $categoryId
                ]),
        ]);
        $optionsDataProvider->sort->defaultOrder = ['parent_id' => SORT_ASC];
        $optionsDataProvider->sort->attributes['categoryName'] = [
            'asc' => ['parent_id' => SORT_ASC],
            'desc' => ['parent_id' => SORT_DESC],
        ];

        return $this->render('index', [
            //'categoriesDataProvider' => $categoriesDataProvider,
            'optionsDataProvider' => $optionsDataProvider,
            'categories' => CarOptions::find()->where(['parent_id' => 0])->all(),
            'categoryFilterId' => $categoryId ? $categoryId : 0,
        ]);
    }

    /**
     * Displays a single CarOptions model.
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
     * Creates a new CarOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CarOptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", "Опция добавлена");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CarOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCategory()
    {
        $model = new CarOptions();
        $model->scenario = CarOptions::SCENARIO_CATEGORY_MANAGE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", "Категория добавлена");
            return $this->redirect(['index']);
        }

        return $this->render('create-category', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CarOptions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
     * Deletes an existing CarOptions model.
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
     * Finds the CarOptions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CarOptions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarOptions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
