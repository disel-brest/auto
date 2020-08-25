<?php

namespace app\modules\admin\controllers;

use app\helpers\PluralForm;
use app\modules\main\models\Ad;
use app\modules\user\forms\LoginForm;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/default/index']);
        }

        $this->layout = 'main-login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login(true)) {
            return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : ['/admin/default/index']));
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionBan()
    {
        $ad = $this->getAd();

        if ($ad->ban()) {
            Yii::$app->session->setFlash("success", "Объявление забанено");
        } else {
            Yii::$app->session->setFlash("danger", "Ошибка...");
        }

        return $this->redirect(['/admin/' . substr(Ad::getTypeUrlId($ad::type()), 0, -1) . '/view', 'id' => $ad->id]);
    }

    public function actionClose()
    {
        $ad = $this->getAd();

        if ($ad->close()) {
            Yii::$app->session->setFlash("success", "Объявление закрыто");
        } else {
            Yii::$app->session->setFlash("danger", "Ошибка...");
        }

        return $this->redirect(['/admin/' . substr(Ad::getTypeUrlId($ad::type()), 0, -1) . '/view', 'id' => $ad->id]);
    }

    public function actionActivate()
    {
        $ad = $this->getAd();

        if ($ad->prolong()) {
            $days = $ad->getActiveTimeLeftInDays();
            Yii::$app->session->setFlash("success", "Объявление активировано на " . PluralForm::get($days, "день", "дня", "дней"));
        } else {
            Yii::$app->session->setFlash("danger", "Ошибка...");
        }

        return $this->redirect(['/admin/' . substr(Ad::getTypeUrlId($ad::type()), 0, -1) . '/view', 'id' => $ad->id]);
    }

    /**
     * @return Ad|\yii\db\ActiveRecord
     * @throws Exception
     */
    private function getAd()
    {
        $id = (int)Yii::$app->request->get('id');
        $type = (int)Yii::$app->request->get('type');
        if (($ad = (Ad::getAdClassByType($type))::findOne($id)) === null) {
            throw new Exception("Объявление не найдено");
        }

        return $ad;
    }
}
