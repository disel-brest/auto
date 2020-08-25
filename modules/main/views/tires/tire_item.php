<?php

use app\helpers\AdHelper;
use app\helpers\PluralForm;
use yii\helpers\Url;
use yii\bootstrap\Html;

/* @var $adTire \app\modules\main\models\AdTire */

$brandName = $adTire->brand_id ? $adTire->brand->name : '';
$modelName = $adTire->model_id ? $adTire->model->name : '';

?>
<div class="group-adverts-item-wrap">
    <a class="group-adverts-item-link" href="<?= Url::to(['/main/tires/view', 'id' => $adTire->id]) ?>">
        <div class="group-adverts-item">
            <div class="group-adverts-item-name-mobile">
                <div>
                    <span class="type"> <?= $adTire->seasonName ?> шины </span>
                    <span class="mark"> <?= $brandName . " " . $modelName ?> </span>
                </div>
                <div>
                    <span class="size"> <?= $adTire->size ?> </span>
                    <span class="radius"> <?= $adTire->radiusName ?> </span>
                </div>
            </div>
            <div class="group-adverts-item-image">
                <img src="<?= $adTire->mainPhoto ?>" alt="<?= $brandName . " " . $modelName ?>">
                <!--<div class="group-adverts-item-image-status <?= AdHelper::getConditionColor($adTire->condition) ?>"><?= $adTire->condition ?></div>-->
            </div>
            <div class="group-adverts-item-about">
                <div class="group-adverts-item-about-name">
                    <span class="type"> <?= $adTire->seasonName ?> шины </span>
                    <span class="mark"> <?= $brandName . " " . $modelName ?> </span>
                    <span class="size"> <?= $adTire->size ?> </span>
                    <span class="radius"> <?= $adTire->radiusName ?> </span>
                </div>
                <ul class="group-adverts-item-about-options">
                    <li class="group-adverts-item-about-par season">
                        <div class="group-adverts-item-about-option-title">Состояние</div>
                        <div class="group-adverts-item-about-option-value"><?= $adTire->isNewName ?></div>
                    </li>
                    <li class="group-adverts-item-about-par radius">
                        <div class="group-adverts-item-about-option-title">Радиус</div>
                        <div class="group-adverts-item-about-option-value"><?= $adTire->radiusName ?></div>
                    </li>
                    <li class="group-adverts-item-about-par size">
                        <div class="group-adverts-item-about-option-title">Размеры</div>
                        <div class="group-adverts-item-about-option-value"><?= $adTire->size ?></div>
                    </li>
                    <li class="group-adverts-item-about-par count">
                        <div class="group-adverts-item-about-option-title">Количество</div>
                        <div class="group-adverts-item-about-option-value"><?= $adTire->amountName ?></div>
                    </li>
                </ul>
                <div class="group-adverts-item-about-descr"><?= $adTire->description ?></div>
                <div class="group-adverts-item-about-city"><?= $adTire->city ?: $adTire->user->city->name ?></div>
                <div class="group-adverts-item-about-price-mobile">
                    <div class="group-adverts-item-about-blr"><?= $adTire->priceNormal ?> <span>р</span></div>
                </div>
            </div>
            <div class="group-adverts-item-about-price">
                <div class="group-adverts-item-about-blr"><?= $adTire->priceNormal ?> <span>бел.руб.</span></div>
                <div class="group-adverts-item-about-price-info">
                    <div class="group-adverts-item-about-val"><?= $adTire->price_usd ? '= ' . $adTire->getPriceNormal(true) . ' y.e' : '' ?></div>
                    <div class="group-adverts-item-about-auction"><?= $adTire->bargain ? "торг" : "" ?></div>
                </div>
                <div class="group-adverts-item-about-status <?= AdHelper::getConditionColor($adTire->condition) ?>">Состояние: <span class="status"><?= $adTire->condition ?></span> из 6</div>
            </div>
            <div class="group-adverts-item-about-bottom">
                <div class="group-adverts-item-about-city"><?= $adTire->city ?: $adTire->user->city->name ?></div>
                <div class="group-adverts-item-about-status <?= AdHelper::getConditionColor($adTire->condition) ?>">Состояние: <span class="status"><?= $adTire->condition ?></span> из 6</div>
            </div>
            <!--<div class="group-adverts-item-about-more">>Подробнее...<span></span></div>-->
            <div class="views"><?= /*/PluralForm::get($adTire->views, "просмотр", "просмотра", "просмотров")*/ $adTire->views ?></div>
        </div>
    </a>
    <div class="category-advert-edit tooltip-has">
        <a href="<?= Url::to(['/main/tires/edit', 'id' => $adTire->id]) ?>" class=""></a>
        <span class="tooltip-popup">редактировать</span>
    </div>
    <div class="category-advert-delete tooltip-has">
        <?= Html::a('', ['/main/tires/remove', 'id' => $adTire->id], [
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
        <span class="tooltip-popup">удалить</span>
    </div>
</div>