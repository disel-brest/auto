<?php

use app\modules\main\components\Counter;
use yii\helpers\Url;

?>
<div class="group-categories">
	<div class="wrap">
	    <ul>
	       <!--  <li<?= Yii::$app->controller->id == 'cars' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/main/cars/index']) ?>">Автомобили</a><span class="count"><?= Yii::$container->get(Counter::class)->get(Counter::CARS_ALL) ?></span></li> -->
	        <li<?= Yii::$app->controller->id == 'parts' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/main/parts/index']) ?>">Автозапчасти б/у</a><span class="count"><?= Yii::$container->get(Counter::class)->get(Counter::PARTS_ALL) ?></span></li>
	        <li<?= Yii::$app->controller->id == 'tires' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/main/tires/index']) ?>">Шины</a><span class="count"><?= Yii::$container->get(Counter::class)->get(Counter::TIRES_ALL) ?></span></li>
	        <li<?= Yii::$app->controller->id == 'wheels' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/main/wheels/index']) ?>">Диски</a><span class="count"><?= Yii::$container->get(Counter::class)->get(Counter::WHEELS_ALL) ?></span></li>
	    </ul>
	</div>
</div>