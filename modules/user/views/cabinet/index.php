<?php

use app\components\Cacher;
use app\helpers\AdHelper;
use app\modules\main\models\Ad;
use app\modules\main\widgets\PartsTableWidget;
use app\modules\user\models\User;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $user User */
/* @var $carsProvider \yii\data\ActiveDataProvider */
/* @var $tiresProvider \yii\data\ActiveDataProvider */
/* @var $wheelsProvider \yii\data\ActiveDataProvider */
/* @var $curType int|null */

?>

<div class="content-left">
    <?= $this->render('@app/modules/user/views/common/user-bar') ?>
</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="#">Главная</a> > <a href="javascript:void(0);">Личный кабинет</a>
    </div>
    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => $user]) ?>
    <div id="my-adverts">
        <div class="group-sort group-adverts">
            <p class="group-adverts-title">Мои объявления <span class="count-adds">(<?= Cacher::userAdCount(Yii::$app->user->id) ?>)</span></p>
            <?= Html::beginForm(['/user/cabinet/index'], 'get', ['id' => 'ad-type-lk-form']) ?>
            <div class="sort-trigger">
                <select name="">
                    <option value="">Авто</option>
                </select>
            </div>
            <div class="sort-trigger">
                <select name="type" onchange="$('#ad-type-lk-form').submit()">
                    <option value="">Все</option>
                    <!-- <option value="<?= Ad::TYPE_CAR ?>"<?= $curType == Ad::TYPE_CAR ? ' selected' : '' ?>>Легковые авто</option> -->
                    <option value="<?= Ad::TYPE_PART ?>"<?= $curType == Ad::TYPE_PART ? ' selected' : '' ?>>Автозапчасти</option>
                    <option value="<?= Ad::TYPE_TIRE ?>"<?= $curType == Ad::TYPE_TIRE ? ' selected' : '' ?>>Шины</option>
                    <option value="<?= Ad::TYPE_WHEEL ?>"<?= $curType == Ad::TYPE_WHEEL ? ' selected' : '' ?>>Диски</option>
                </select>
            </div>
            <?= Html::endForm() ?>
        </div>

        <?php if (!$curType || $curType == Ad::TYPE_PART) {
            echo PartsTableWidget::widget(['cabinet' => true]);
        } ?>

        <?php /*if ((!$curType || $curType == Ad::TYPE_CAR) && $carsProvider->models): ?>
        <div class="auto-adverts category-block">
            <div class="wrap">
                <div class="category-title">Авто - Транспорт</div>
                <div class="prolongue-group-btn ad-prolong-link" data-ad-type="<?= Ad::TYPE_CAR ?>" data-group="1">Продлить все объявления группы</div>
                <div class="category-list">
                    <div class="category-advert">
                        <?php foreach ($carsProvider->models as $adCar) : ?>
                            <div style="position:relative">
                                <?= $this->render("@app/modules/main/views/cars/car_item", ['adCar' => $adCar]) ?>
                                <div class="category-advert-extend-wrap">
                                    <div class="category-advert-extend">
                                        <p>
                                            <?= AdHelper::activeTimeString($adCar) ?>
                                        </p>
                                        <?php if ($adCar->status == Ad::STATUS_ACTIVE || $adCar->status == Ad::STATUS_INACTIVE) : ?>
                                            <a
                                                href="javascript:void(0)"
                                                class="category-advert-extend-btn <?= $adCar->activeTimeLeftInDays <= 29 ? "ad-prolong-link" : "not-active" ?>"
                                                data-ad-type="<?= Ad::TYPE_CAR ?>"
                                                data-ad-id="<?= $adCar->id ?>"
                                            >Продлить</a>
                                        <?php endif; ?>
                                        <!--<a href="<?= Url::to(['/main/cars/edit', 'id' => $adCar->id]) ?>" class="category-advert-edit">Редактировать</a>-->
                                        <!--<?= Html::a('Удалить', ['/main/cars/remove', 'id' => $adCar->id], [
                                            'class' => 'category-advert-delete',
                                            'data' => [
                                                'confirm' => 'Вы уверены?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>-->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif;*/ ?>

        <?php if ((!$curType || $curType == Ad::TYPE_TIRE) && $tiresProvider->models): ?>
        <div class="tires-adverts category-block">
            <div class="category-title">Авто - Шины</div>
            <div class="prolongue-group-btn ad-prolong-link" data-ad-type="<?= Ad::TYPE_TIRE ?>" data-group="1">Продлить все объявления группы</div>
            <div class="category-list">
                <div class="category-advert">
                    <?php foreach ($tiresProvider->models as $adTire) : ?>
                        <div style="position:relative">
                            <?= $this->render("@app/modules/main/views/tires/tire_item", ['adTire' => $adTire]) ?>
                            <div class="category-advert-extend-wrap">
                                <div class="category-advert-extend">
                                    <p class="category-advert-extend-text">
                                        <?= AdHelper::activeTimeString($adTire) ?>
                                    </p>
                                    <?php if ($adTire->status == Ad::STATUS_ACTIVE || $adTire->status == Ad::STATUS_INACTIVE) : ?>
                                        <a
                                            href="javascript:void(0)"
                                            class="category-advert-extend-btn <?= $adTire->activeTimeLeftInDays <= 29 ? "ad-prolong-link" : "not-active" ?>"
                                            data-ad-type="<?= Ad::TYPE_TIRE ?>"
                                            data-ad-id="<?= $adTire->id ?>"
                                        >Продлить</a>
                                    <?php endif; ?>
                                    <!--<a href="<?= Url::to(['/main/tires/edit', 'id' => $adTire->id]) ?>" class="category-advert-edit">Редактировать</a>-->
                                    <!--<?= Html::a('Удалить', ['/main/tires/remove', 'id' => $adTire->id], [
                                        'class' => 'category-advert-delete',
                                        'data' => [
                                            'confirm' => 'Вы уверены?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>-->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ((!$curType || $curType == Ad::TYPE_WHEEL) && $wheelsProvider->models): ?>
        <div class="wheels-adverts category-block">
            <div class="category-title">Авто - Диски</div>
            <div class="prolongue-group-btn ad-prolong-link" data-ad-type="<?= Ad::TYPE_WHEEL ?>" data-group="1">Продлить все объявления группы</div>
            <div class="category-list">
                <?php foreach ($wheelsProvider->models as $adWheel) : ?>
                    <div class="category-advert">
                        <?= $this->render("@app/modules/main/views/wheels/wheel_item", ['adWheel' => $adWheel]) ?>
                        <div class="category-advert-extend-wrap">
                            <div class="category-advert-extend">
                                <p class="category-advert-extend-text">
                                    <?= AdHelper::activeTimeString($adWheel) ?>
                                </p>
                                <?php if ($adWheel->status == Ad::STATUS_ACTIVE || $adWheel->status == Ad::STATUS_INACTIVE) : ?>
                                    <a
                                        href="javascript:void(0)"
                                        class="category-advert-extend-btn <?= $adWheel->activeTimeLeftInDays <= 29 ? "ad-prolong-link" : "not-active" ?>"
                                        data-ad-type="<?= Ad::TYPE_WHEEL ?>"
                                        data-ad-id="<?= $adWheel->id ?>"
                                    >Продлить</a>
                                <?php endif; ?>
                                <!--<a href="<?= Url::to(['/main/wheels/edit', 'id' => $adWheel->id]) ?>" class="category-advert-edit">Редактировать</a>-->
                                <!--<?= Html::a('Удалить', ['/main/wheels/remove', 'id' => $adWheel->id], [
                                    'class' => 'category-advert-delete',
                                    'data' => [
                                        'confirm' => 'Вы уверены?',
                                        'method' => 'post',
                                    ],
                                ]) ?>-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="all-advert-extend-btn-wrap">
             <a href="<?= Url::to(['/main/default/prolong-all']) ?>" class="all-advert-extend-btn">Продлить все объявления</a>
        </div>
    </div>
</div>