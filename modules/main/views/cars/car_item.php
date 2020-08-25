<?php

use app\helpers\PluralForm;
use yii\helpers\Url;

/* @var $adCar \app\modules\main\models\AdCar */

?>
<div class="group-adverts-item clearfix">
    <a class="group-adverts-item-link" href="<?= Url::to(['/main/cars/view', 'id' => $adCar->id]) ?>">
        <div class="group-adverts-item-image">
            <img src="<?= $adCar->mainPhoto ?>" alt="<?= $adCar->fullName ?>">
        </div>
        <div class="group-adverts-item-about">
            <div class="group-adverts-item-about-name">
                <span class="mark"> <?= $adCar->brand->name ?> </span><span class="model"> <?= $adCar->model->name ?> </span><span class="year"> <?= $adCar->year ?> г.в.</span>
            </div>
            <ul class="group-adverts-item-about-options">
                <li class="group-adverts-item-about-par engine"><span></span><?= $adCar->engineVolume ?> <?= $adCar->fuelName ?></li>
                <li class="group-adverts-item-about-par mileage"><span></span><?= $adCar->odometerNormalize ?></li>
                <li class="group-adverts-item-about-par transm"><span></span><?= $adCar->transmissionName ?></li>
                <li class="group-adverts-item-about-par color"><span></span><?= $adCar->colorName ?></li>
            </ul>
            <div class="group-adverts-item-about-descr"><?= $adCar->description ?></div>
            <div class="group-adverts-item-about-city"><?= $adCar->city ?: $adCar->user->city->name ?></div>
        </div>
        <div class="group-adverts-item-about-price">
            <div class="group-adverts-item-about-blr"><?= $adCar->priceNormal ?> <span>бел.руб.</span></div>
            <div class="group-adverts-item-about-val">= <?= $adCar->getPriceNormal(true) ?> $</div>
            <div class="group-adverts-item-about-auction"><?= $adCar->bargain ? "Торг" : "" ?></div>
        </div>
        <div class="group-adverts-item-about-more">>Подробнее...<span></span></div>
        <div class="views"><?= PluralForm::get($adCar->views, "просмотр", "просмотра", "просмотров") ?></div>
    </a>
</div>