<?php

namespace app\helpers;


use app\modules\main\models\City;

class GeoHelper
{
    public static function getRegionByCity($city)
    {
        return City::find()->select('region')->where(['city' => $city])->scalar();
    }

    public static function getCityById($id)
    {
        return City::find()->where(['id' => $id])->limit(1)->one();
    }

    public static function getCityIdByName($city)
    {
        return City::find()->select('id')->where(['name' => $city])->scalar();
    }
}