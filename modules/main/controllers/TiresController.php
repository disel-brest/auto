<?php

namespace app\modules\main\controllers;


use app\modules\main\forms\AddTireForm;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdTire;
use app\modules\main\models\filters\CarsFilter;
use app\modules\main\models\filters\TiresFilter;
use app\modules\main\models\TireModel;
use app\rbac\Rbac;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TiresController extends Controller
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
                    'get-models' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $filter = new TiresFilter();
        $dataProvider = $filter->search(Yii::$app->request->get());

        return $this->render('index', [
            'adTires' => $dataProvider->getModels(),
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
        $formModel = new AddTireForm();
        $formModel->tire_type = 1; // Легковые по умолчанию

        if ($formModel->load(Yii::$app->request->post()) && $formModel->add()) {
            return $this->redirect(['/main/tires/view', 'id' => $formModel->id]);
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
        $adTire = $this->findModel($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adTire->user_id])) {
            throw new ForbiddenHttpException();
        }

        $formModel = new AddTireForm($adTire);

        if ($formModel->load(Yii::$app->request->post()) && $formModel->add()) {
            if (Yii::$app->request->get('from_cp')) {
                Yii::$app->session->setFlash("success", "Данные сохранены");
                $url = '/admin/tire/view';
            } else {
                $url = '/main/tires/view';
            }
            return $this->redirect([$url, 'id' => $formModel->id]);
        }

        return $this->render('add', [
            'formModel' => $formModel,
        ]);
    }

    public function actionFilter()
    {
        $filter = new TiresFilter();

        return $this->render('filter', [
            'filter' => $filter,
        ]);
    }

    public function actionView($id)
    {
        $adTire = $this->findModel($id);

        if ($adTire->status != AdCar::STATUS_ACTIVE && !Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adTire->user_id])) {
            throw new ForbiddenHttpException("Объявление неактивно");
        }

        $adTire->updateCounters(['views' => 1]);

        return $this->render('view', [
            'adTire' => $adTire
        ]);
    }

    /**
     * @param integer $id
     * @return Response
     */
    public function actionRemove($id)
    {
        $adCar = $this->findModel($id);
        $adCar->status = AdCar::STATUS_DELETED;
        $adCar->save(false);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionPublish($id)
    {
        $adTire = $this->findModel($id);

        if ($adTire->status != Ad::STATUS_PREVIEW && !Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adTire->user_id])) {
            throw new ForbiddenHttpException("Нет прав либо объявление не в статусе предпросмотра");
        }

        $adTire->status = AdCar::STATUS_ACTIVE;
        $adTire->save();

        return $this->redirect(['/main/tires/view', 'id' => $adTire->id]);
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
     * @return AdTire the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdTire::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Объявление не найдено.');
        }
    }
}
