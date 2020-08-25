<?php

namespace app\modules\main\controllers;

use app\helpers\AdHelper;
use app\helpers\CounterHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use app\modules\main\models\City;
use app\modules\main\models\Complaint;
use app\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['send-complaint', 'prolong'],
                'rules' => [
                    [
                        'actions' => ['send-complaint', 'prolong', 'prolong-all'],
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_USER],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-models' => ['post'],
                    'send-complaint' => ['post'],
                    'prolong' => ['post'],
                    'get-phone' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'get-cities' => [
                'class' => 'app\components\geo\GetCitiesAction',
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
     * @return array
     */
    public function actionGetModels()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $brand_id = Yii::$app->request->post('brand_id');
        if (!preg_match('/^[0-9]+$/', $brand_id)) {
            return ['result' => false, 'message' => 'Такой марки нет'];
        }

        $models = AutoModel::find()
            ->select(['id', 'name'])
            ->where(['brand_id' => $brand_id])
            ->asArray()
            ->all();

        $result = [];
        foreach ($models as $model) {
            $result[] = array_merge($model, [
                'count' => CounterHelper::spanCounter(
                    $model['id'],
                    CounterHelper::TYPE_CAR_MODEL,
                    Yii::$app->request->post('cc'),
                    Yii::$app->request->post('ac'),
                    false
                )
            ]);
        }

        return ['result' => 'success', 'models' => $result];
    }

    /**
     * @return array
     */
    public function actionSendComplaint()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $adId = (int)Yii::$app->request->post('ad_id');
        $adType = (int)Yii::$app->request->post('ad_type');
        $message = Yii::$app->request->post('message');

        if (!$adId || !$adType || !$message) {
            return ["result" => false];
        }

        if (!Yii::$app->user->isGuest && Complaint::find()->where(['user_id' => Yii::$app->user->id, 'ad_type' => $adType, 'ad_id' => $adId])->exists()) {
            return ["result" => false, "message" => "На это объявление Вы уже подавали жалобу"];
        }

        $complaint = new Complaint([
            'ad_type' => $adType,
            'ad_id' => $adId,
            'user_id' => Yii::$app->user->id,
            'message' => $message,
            'status' => Complaint::STATUS_NOT_VIEWED
        ]);

        if ($complaint->save()) {
            return ["result" => "success"];
        }

        return ["result" => false, "errors" => $complaint->getErrors()];
    }

    /**
     * @return array
     */
    public function actionProlong()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $adIds = Yii::$app->request->post('ad_id', '');
        $adType = (int) Yii::$app->request->post('ad_type');
        $isGroup = (bool) Yii::$app->request->post('is_group');

        if (!$isGroup && (!preg_match('/^[0-9]+(,[0-9]+)*$/', $adIds) || !$adType)) {
            return ["result" => false];
        }

        $adIds = explode(",", $adIds);
        if (!$isGroup && count($adIds) == 1) {
            if (($ad = (Ad::getAdClassByType($adType))::findOne($adIds[0])) === null) {
                return ["result" => false, "message" => "Объявление не найдено"];
            }

            if (!in_array($ad->status, [Ad::STATUS_ACTIVE, Ad::STATUS_INACTIVE, Ad::STATUS_CLOSED])) {
                return ["result" => false, "message" => "Объявление со статусом '" . $ad->statusName . "' продлить нельзя"];
            }

            if (!isset($ad->active_till)) {
                return ["result" => false];
            }

            if ($ad->prolong()) {
                return ["result" => "success"];
            }
        } else {
            $ad = Ad::getAdClassByType($adType);

            $condition = [];
            $condition['status'] = [Ad::STATUS_ACTIVE, Ad::STATUS_INACTIVE];
            $isGroup ? $condition['user_id'] = Yii::$app->user->id : $condition['id'] = $adIds;

            $ad::updateAll([
                'active_till' => time() + Yii::$app->params['ad.defaultActiveTime'],
                'status' => Ad::STATUS_ACTIVE
            ], $condition);
            return ["result" => "success"];
        }

        return ["result" => false];
    }

    /**
     * @return Response
     */
    public function actionProlongAll()
    {
        AdHelper::prolongAll(Yii::$app->user->id);
        return $this->redirect(['/user/cabinet/index']);
    }

    /**
     * @return array
     */
    public function actionGetPhone()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $adId = (int)Yii::$app->request->post('ad_id');
        $adType = (int)Yii::$app->request->post('ad_type');

        if (!$adId || !$adType) {
            return ["result" => false];
        }

        if (($ad = (Ad::getAdClassByType($adType))::findOne($adId)) === null) {
            return ["result" => false, "message" => "Объявление не найдено"];
        }

        if ($ad->status == Ad::STATUS_ACTIVE) {
            $ad->updateCounters(['views' => 1]);
            $html = '<span>' . $ad->user->phone_operator . '</span><span class="phone-part">' . $ad->user->phone . '</span>';
            return ["result" => "success", "html" => $html];
        }

        return ["result" => false, "message" => "Объявление не активно"];
    }
}
