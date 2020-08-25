<?php

namespace app\board\repositories;


use app\components\Cacher;
use app\modules\main\models\City;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

class CityRepository
{
    public static function getCitiesByRegion($region)
    {
        return \Yii::$app->cache->getOrSet(Cacher::KEY_CITIES_BY_REGION, function () use ($region) {
            return ArrayHelper::map(City::find()->where(['region' => $region])->orderBy(['name' => SORT_ASC])->asArray()->all(), 'id', 'name');
        }, Cacher::DURATION_CITIES_BY_REGION, new TagDependency(['tags' => $region]));
    }
}