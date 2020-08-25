<?php

namespace app\modules\main\controllers;


use app\helpers\AdHelper;
use app\modules\main\forms\AddTireForm;
use app\modules\main\forms\AddWheelForm;
use app\modules\main\models\Ad;
use app\modules\main\models\AdWheel;
use app\modules\main\models\filters\TiresFilter;
use app\modules\main\models\filters\WheelsFilter;
use app\modules\main\models\TireModel;
use app\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class WheelsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add', 'remove'],
                'rules' => [
                    [
                        'actions' => ['add', 'remove'],
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
     * @return string
     */
    public function actionIndex()
    {
        $filter = new WheelsFilter();
        $dataProvider = $filter->search(Yii::$app->request->get());

        return $this->render('index', [
            'adWheels' => $dataProvider->getModels(),
            'filter' => $filter,
            'pagination' => $dataProvider->getPagination(),
            'sort' => $dataProvider->sort->attributeOrders,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $formModel = new AddWheelForm();
        $formModel->auto_type = 1;

        if ($formModel->load(Yii::$app->request->post()) && $formModel->add()) {
            return $this->redirect(['/main/wheels/view', 'id' => $formModel->id]);
        }

        return $this->render('add', [
            'formModel' => $formModel,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws ForbiddenHttpException
     */
    public function actionEdit($id)
    {
        $adWheel = $this->findModel($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adWheel->user_id])) {
            throw new ForbiddenHttpException();
        }

        $formModel = new AddWheelForm($adWheel);
        $formModel->scenario = AddWheelForm::SCENARIO_UPDATE;

        if ($formModel->load(Yii::$app->request->post()) && $formModel->add()) {
            if (Yii::$app->request->get('from_cp')) {
                Yii::$app->session->setFlash("success", "Данные сохранены");
                $url = '/admin/wheel/view';
            } else {
                $url = '/main/wheels/view';
            }
            return $this->redirect([$url, 'id' => $formModel->id]);
        }

        return $this->render('add', [
            'formModel' => $formModel,
        ]);
    }

    public function actionFilter()
    {
        $filter = new WheelsFilter();

        return $this->render('filter', [
            'filter' => $filter,
        ]);
    }

    public function actionView($id)
    {
        $adWheel = $this->findModel($id);

        if ($adWheel->status != Ad::STATUS_ACTIVE && !Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adWheel->user_id])) {
            throw new ForbiddenHttpException("Объявление неактивно");
        }

        $adWheel->updateCounters(['views' => 1]);

        return $this->render('view', [
            'adWheel' => $adWheel
        ]);
    }

    /**
     * @param integer $id
     * @return array
     */
    public function actionRemove($id)
    {
        $adWheel = $this->findModel($id);
        $adWheel->status = Ad::STATUS_DELETED;
        $adWheel->save(false);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionPublish($id)
    {
        $adWheel = $this->findModel($id);

        if ($adWheel->status != Ad::STATUS_PREVIEW && !Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adWheel->user_id])) {
            throw new ForbiddenHttpException("Нет прав либо объявление не в статусе предпросмотра");
        }

        $adWheel->status = Ad::STATUS_ACTIVE;
        $adWheel->save();

        return $this->redirect(['/main/wheels/view', 'id' => $adWheel->id]);
    }

    /**
     * @return array
     */
    public function actionGetModels()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int)Yii::$app->request->post("id");
        $items = TireModel::find()->select(['id', 'name'])->where(['brand_id' => $id])->all();

        return ["result" => "success", "items" => $items];
    }

    /**
     * @param integer $id
     * @return AdWheel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdWheel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Объявление не найдено.');
        }
    }
}
