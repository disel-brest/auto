<?php
/**
 * Created by PhpStorm.
 * User: pa3py6aka
 * Date: 03.03.17
 * Time: 21:01
 */

namespace app\components\geo;


use app\modules\main\models\City;
use Yii;
use yii\base\Action;
use yii\web\Response;

class GetCitiesAction extends Action
{
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->request->isAjax) {
            return ['result' => 'error'];
        }

        $city = trim(Yii::$app->request->getQueryParam('city'));
        $cities = City::find()
            ->select(['name'])
            ->where(['like', 'name', $city . '%', false])
            ->column();

        return $cities;
    }
}