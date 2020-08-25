<?php

namespace app\components;


use app\modules\main\components\Counter;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $container->setSingleton(Counter::class, [], [
            $app->getCache()
        ]);
    }
}