<?php

namespace app\helpers;


use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdWheel;
use app\modules\main\models\AutoBrand;
use Yii;

class CounterHelper
{
    const TYPE_CAR_BRAND = 'carBrand';
    const TYPE_CAR_MODEL = 'carModel';

    public static function spanCounter($id, $type, $controllerId = null, $actionId = null, $withSpan = true)
    {
        switch ($type) {
            case self::TYPE_CAR_BRAND:
                $attribute = 'brand_id';
                break;
            case self::TYPE_CAR_MODEL:
                $attribute = 'model_id';
                break;
            default: $attribute = 'brand_id';
        }

        $controllerId = $controllerId ?: Yii::$app->controller->id;
        $actionId = $actionId ?: Yii::$app->controller->action->id;

        $count = null;
        if ($controllerId == 'parts' && $actionId == 'index') {
            $count = self::getCount(AdPart::class, $id, $attribute);
        } else if ($controllerId == 'cars' && $actionId == 'index') {
            $count = self::getCount(AdCar::class, $id, $attribute);
        } else if ($controllerId == 'wheels' && $actionId == 'index') {
            $count = self::getCount(AdWheel::class, $id, 'auto_brand_id');
        }

        if ($count !== null) {
            return $withSpan ? '<span class="counter">' . $count . '</span>' : $count;
        }

        return '';
    }

    /**
     * @param $ad Ad
     * @param $id integer
     * @param $attribute string
     * @return integer
     */
    private static function getCount($ad, $id, $attribute)
    {
        return $ad::find()->cache(60)->where([$attribute => $id])->count();
    }
}