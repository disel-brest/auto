<?php

use app\helpers\AdHelper;
use app\helpers\PluralForm;
use yii\helpers\Url;
use yii\bootstrap\Html;

/* @var $adWheel \app\modules\main\models\AdWheel */

?>
<div class="group-adverts-item-wrap">
    <a class="group-adverts-item-link" href="<?= Url::to(['/main/wheels/view', 'id' => $adWheel->id]) ?>">
        <div class="group-adverts-item">
            <div class="group-adverts-item-name-mobile">
                <span class="type"> <?= $adWheel->wheelTypeName ?> диски </span>
                <span class="auto"> <?= $adWheel->autoBrand ? $adWheel->autoBrand->name : '' ?> </span>
                <span class="radius"><?= $adWheel->radiusName ?></span>
            </div>
            <div class="group-adverts-item-image">
                <img src="<?= $adWheel->mainPhoto ?>" alt="<?= $adWheel->autoTypeName ?>">
                <!--<div class="group-adverts-item-image-status <?= AdHelper::getConditionColor($adWheel->condition) ?>"><?= $adWheel->condition ?></div>-->
            </div>
            <div class="group-adverts-item-about">
                <div class="group-adverts-item-about-name">
                    <span class="type"> <?= $adWheel->wheelTypeName ?> диски </span>
                    <span class="auto"> <?= $adWheel->autoBrand ? $adWheel->autoBrand->name : '' ?> </span>
                    <span class="radius"><?= $adWheel->radiusName ?></span>
                </div>
                <ul class="group-adverts-item-about-options">
                    <li class="group-adverts-item-about-par type">
                        <div class="group-adverts-item-about-option-title">Состояние</div>
                        <div class="group-adverts-item-about-option-value"><?= $adWheel->isNewName ?></div>
                    </li>
                    <li class="group-adverts-item-about-par radius">
                        <div class="group-adverts-item-about-option-title">Радиус</div>
                        <div class="group-adverts-item-about-option-value"><?= $adWheel->radiusName ?></div>
                    </li>
                    <li class="group-adverts-item-about-par count-boltes">
                        <div class="group-adverts-item-about-option-title">Кол-во болтов</div>
                        <div class="group-adverts-item-about-option-value"><?= PluralForm::get($adWheel->bolts, 'болт', 'болта', 'болтов') ?></div>
                    </li>
                    <li class="group-adverts-item-about-par count">
                        <div class="group-adverts-item-about-option-title">Количество</div>
                        <div class="group-adverts-item-about-option-value"><?= $adWheel->amountName ?></div>
                    </li>
                </ul>
                <div class="group-adverts-item-about-descr"><?= $adWheel->description ?></div>
                <div class="group-adverts-item-about-city"><?= $adWheel->city ?: $adWheel->user->city->name ?></div>
                <div class="group-adverts-item-about-price-mobile">
                    <div class="group-adverts-item-about-blr"><?= $adWheel->priceNormal ?> <span>р</span></div>
                </div>
            </div>
            <div class="group-adverts-item-about-price">
                <div class="group-adverts-item-about-blr"><?= $adWheel->priceNormal ?> <span>бел.руб.</span></div>
                <div class="group-adverts-item-about-price-info">
                    <div class="group-adverts-item-about-val"><?= $adWheel->price_usd ? '= ' . $adWheel->getPriceNormal(true) . ' y.e' : '' ?></div>
                    <div class="group-adverts-item-about-auction"><?= $adWheel->bargain ? "торг" : "" ?></div>
                </div>
                <div class="group-adverts-item-about-status <?= AdHelper::getConditionColor($adWheel->condition) ?>">Состояние <span class="status"><?= $adWheel->condition ?></span> из 6</div>
            </div>
            <div class="group-adverts-item-about-bottom">
                <div class="group-adverts-item-about-city"><?= $adWheel->city ?: $adWheel->user->city->name ?></div>
                <div class="group-adverts-item-about-status <?= AdHelper::getConditionColor($adWheel->condition) ?>">Состояние <span class="status"><?= $adWheel->condition ?></span> из 6</div>
            </div>
            <!--<div class="group-adverts-item-about-more">>Подробнее...<span></span></div>-->

            <div class="views"><?= /*PluralForm::get($adWheel->views, "просмотр", "просмотра", "просмотров")*/ $adWheel->views ?></div>
        </div>
    </a>
    <div class="category-advert-edit tooltip-has">
        <a href="<?= Url::to(['/main/wheels/edit', 'id' => $adWheel->id]) ?>"></a>
        <span class="tooltip-popup">редактировать</span>
    </div>
    <div class="category-advert-delete tooltip-has">
        <?= Html::a('', ['/main/wheels/remove', 'id' => $adWheel->id], [
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
        <span class="tooltip-popup">удалить</span>
        </div>
</div>