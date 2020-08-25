<?php
/**
 * Created by PhpStorm.
 * User: pa3py6aka
 * Date: 03.03.17
 * Time: 20:12
 */

namespace app\components;


use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use app\modules\main\models\AdWheel;
use Yii;

class Cacher
{
    const KEY_ALL_CITIES = "all_cities";
    const DURATION_ALL_CITIES = 86400 * 365;
    const KEY_USER_AD_COUNT = "user-ad-count-";
    const DURATION_USER_AD_COUNT = 86400;

    const KEY_CITIES_BY_REGION = 'cities-by-region';
    const DURATION_CITIES_BY_REGION = 86400 * 30 * 6;

    public static function userAdCount($userId)
    {
        return Yii::$app->cache->getOrSet(self::KEY_USER_AD_COUNT . $userId, function () use ($userId) {
            return self::getAdCountByUserId($userId);
        }, self::DURATION_USER_AD_COUNT);
    }

    public static function updateAdCount($userId)
    {
        $adCount = self::getAdCountByUserId($userId);
        Yii::$app->cache->set(self::KEY_USER_AD_COUNT . $userId, $adCount, self::DURATION_USER_AD_COUNT);
    }

    private static function getAdCountByUserId($userId)
    {
        return AdTire::find()->where(['user_id' => $userId, 'status' => [Ad::STATUS_ACTIVE, Ad::STATUS_CLOSED]])->count()
            + AdPart::find()->where(['user_id' => $userId, 'status' => [Ad::STATUS_ACTIVE, Ad::STATUS_CLOSED]])->count()
            + AdWheel::find()->where(['user_id' => $userId, 'status' => [Ad::STATUS_ACTIVE, Ad::STATUS_CLOSED]])->count()
            + AdCar::find()->where(['user_id' => $userId, 'status' => [Ad::STATUS_ACTIVE, Ad::STATUS_CLOSED]])->count();
    }
}