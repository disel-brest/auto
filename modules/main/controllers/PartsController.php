<?php

namespace app\modules\main\controllers;

use app\modules\main\forms\AddPartForm;
use app\modules\main\forms\PartForm;
use app\modules\main\models\AdPart;
use app\modules\main\models\filters\PartsFilter;
use app\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `main` module
 */
class PartsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add', 'remove', 'update'],
                'rules' => [
                    [
                        'actions' => ['add', 'remove', 'update'],
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_USER],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove' => ['post'],
                ],
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
     * @return array|string
     */
    public function actionAdd()
    {
        $formModel = new AddPartForm();

        if (Yii::$app->request->isAjax && $formModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($formModel->add()) {
                return ['result' => 'success'];
            } else {
                return ['result' => false, 'errors' => $formModel->getErrors()];
            }
        }

        $formModel->brand_id = Yii::$app->request->get('b');
        $formModel->model_id = Yii::$app->request->get('m');
        $formModel->fuel_id = Yii::$app->request->get('f');
        $formModel->engine_volume = Yii::$app->request->get('e');
        $formModel->year = Yii::$app->request->get('y');
        $formModel->body_style = Yii::$app->request->get('s');

        return $this->render('add', [
            'formModel' => $formModel,
        ]);
    }

    public function actionUpdate($id)
    {
        if (($adPart = AdPart::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }

        $model = new PartForm($adPart);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/user/cabinet/index']);
        }

        return $this->render('@app/modules/main/views/parts/update', [
            'model' => $model,
            'adPart' => $adPart
        ]);
    }

    /**
     * @param integer $id
     * @return array
     */
    public function actionRemove($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (($part = AdPart::findOne($id)) === null) {
            return ['result' => false, 'message' => 'Объявление не найдено'];
        }

        $part->status = AdPart::STATUS_DELETED;
        if ($part->save(false)) {
            return ['result' => 'success'];
        }

        return ['result' => false, 'message' => 'Ошибка удаления'];
    }
}
