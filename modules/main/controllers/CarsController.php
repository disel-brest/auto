<?php

namespace app\modules\main\controllers;

use app\modules\main\forms\AddCarForm;
use app\modules\main\models\AdCar;
use app\modules\main\models\filters\CarsFilter;
use app\rbac\Rbac;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CarsController extends Controller
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
        $filter = new CarsFilter();
        $dataProvider = $filter->search(Yii::$app->request->get());

        return $this->render('index', [
            'adCars' => $dataProvider->getModels(),
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
        $formModel = new AddCarForm();

        if ($formModel->load(Yii::$app->request->post()) && $formModel->add()) {
            return $this->redirect(['/main/cars/view', 'id' => $formModel->id]);
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
        $adCar = $this->findModel($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adCar->user_id])) {
            throw new ForbiddenHttpException();
        }

        $formModel = new AddCarForm($adCar);

        if ($formModel->load(Yii::$app->request->post()) && $formModel->add()) {
            if (Yii::$app->request->get('from_cp')) {
                Yii::$app->session->setFlash("success", "Данные сохранены");
                $url = '/admin/car/view';
            } else {
                $url = '/main/cars/view';
            }
            return $this->redirect([$url, 'id' => $formModel->id]);
        }

        return $this->render('add', [
            'formModel' => $formModel,
        ]);
    }

    /**
     * @return string
     */
    public function actionFilter()
    {
        $filter = new CarsFilter();

        return $this->render('filter', [
            'filter' => $filter,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $adCar = $this->findModel($id);

        if ($adCar->status != AdCar::STATUS_ACTIVE && !Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adCar->user_id])) {
            throw new ForbiddenHttpException("Объявление неактивно");
        }

        $adCar->updateCounters(['views' => 1]);

        return $this->render('view', [
            'adCar' => $adCar
        ]);
    }

    /**
     * @param integer $id
     * @return array
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
        $adCar = $this->findModel($id);

        if ($adCar->status != AdCar::STATUS_PREVIEW && !Yii::$app->user->can(Rbac::PERMISSION_AD_MANAGE, ['user_id' => $adCar->user_id])) {
            throw new ForbiddenHttpException("Нет прав либо объявление не в статусе предпросмотра");
        }

        $adCar->status = AdCar::STATUS_ACTIVE;
        $adCar->save();

        return $this->redirect(['/main/cars/view', 'id' => $adCar->id]);
    }

    /**
     * @param integer $id
     * @return AdCar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdCar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Объявление не найдено.');
        }
    }
}
