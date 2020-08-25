<?php

namespace app\modules\main\components;


use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use app\modules\main\models\AdWheel;
use yii\caching\Cache;

class Counter
{
    const PARTS_ALL = 0;
    const CARS_ALL = 1;
    const TIRES_ALL = 2;
    const WHEELS_ALL = 3;

    const KEY_PREFIX = "ad-counter-";

    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function get($id)
    {
        return $this->cache->get(self::KEY_PREFIX . $id);
    }

    public function update()
    {
        $this->cache->set(self::KEY_PREFIX . self::PARTS_ALL, AdPart::find()->active()->count());
        $this->cache->set(self::KEY_PREFIX . self::CARS_ALL, AdCar::find()->active()->count());
        $this->cache->set(self::KEY_PREFIX . self::TIRES_ALL, AdTire::find()->active()->count());
        $this->cache->set(self::KEY_PREFIX . self::WHEELS_ALL, AdWheel::find()->active()->count());
    }
}