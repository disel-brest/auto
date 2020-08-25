<?php

namespace app\components\geo;

use app\components\Cacher;
use app\modules\main\models\City;
use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pa3py6aka
 * Date: 03.03.17
 * Time: 19:50
 */
class Geo extends Object
{
    private $citiesArray = null;

    /**
     * @return array
     */
    public function getCitiesArray()
    {
        if ($this->citiesArray == null) {
            $this->citiesArray = \Yii::$app->cache->getOrSet(Cacher::KEY_ALL_CITIES, function () {
                return ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name');
            }, Cacher::DURATION_ALL_CITIES);
        }

        return $this->citiesArray;
    }
}