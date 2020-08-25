<?php

use app\board\helpers\PhotoHelper;
use app\helpers\AdHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\widgets\UrgentSaleWidget;
use app\rbac\Rbac;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $adTire \app\modules\main\models\AdTire */

?>
<div class="content-left">
    <div class="about-advert">
        <div class="about-advert-item count-views">Кол-во просмотров: <span><?= $adTire->views ?></span></div>
        <div class="about-advert-item number-advert">№ объявления: <span><?= $adTire->id ?></span></div>
        <div class="about-advert-item data-create">Дата подачи объявления: <span><?= Yii::$app->formatter->asDate($adTire->created_at) ?></span></div>
        <?php if (Yii::$app->user->can(Rbac::PERMISSION_USER)) : ?>
            <div class="complain">
                <a href="javascript:void(0)" class="complaint-link" data-ad-id="<?= $adTire->id ?>" data-ad-type="<?= AdCar::type() ?>">пожаловаться</a>
            </div>
        <?php endif; ?>
    </div>

    <?= $this->render("../common/about-user-block-in-ad", ['user' => $adTire->user, 'ad' => $adTire]) ?>

</div>
<div class="content-right inner">
    <div class="breadcrumbs">
        <a href="/">Главная</a> > <a href="<?= Url::to(['/main/tires/index']) ?>">Шины</a> > <a href="javascript:void(0);"><?= $adTire->getFullName() ?></a>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="card-title">
                <h2>
                    <span class="type"> <?= $adTire->seasonName ?> шины </span>
                    <span class="mark"> <?= $adTire->brand->name ?> </span>
                    <span class="mark"> <?= $adTire->model_id ? $adTire->model->name : '' ?> </span>
                    <span class="size"> <?= $adTire->size ?> </span>
                    <span class="radius"> <?= $adTire->radiusName ?> </span>
                </h2>
            </div>
            <div class="card-title-mobile">
                <div class="card-title-mobile-date">Дата подачи объявления: <span><?= Yii::$app->formatter->asDate($adTire->created_at) ?></span></div>
                <div class="card-title-mobile-views"><?= $adTire->views ?></div>
            </div>
            <div class="card-gallery">
                <?php if ($adTire->hasPhoto()) : ?>
                    <div class="status <?= AdHelper::getConditionColor($adTire->condition) ?>"><?= $adTire->condition ?></div>
                    <?php if (count($adTire->photos) > 0) : ?>
                    <div class="gallery-items">
                        <?php foreach ($adTire->photos as $photo) : ?>
                            <div class="card-gallery-item">
                                <?php if (count($adTire->photos) == 1): ?>
                                    <img src="<?= $adTire->mainPhoto ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="fotorama" data-nav="thumbs" data-allowfullscreen="true">
                    <?php if ($adTire->hasPhoto()) : ?>
                        <?php foreach ($adTire->photos as $photo) : ?>
                            <a href="<?= $adTire->getFilesPath(true) . "/" . PhotoHelper::getNameFor($photo, PhotoHelper::TYPE_MN) ?>">
                                <img src="<?= $adTire->getFilesPath(true) . "/" . PhotoHelper::getNameFor($photo, PhotoHelper::TYPE_MN) ?>" alt="">
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-descr">
                <div class="card-descr-price">
                    <div class="card-descr-price-blr">
                        <?= $adTire->priceNormal ?> <span>руб.</span>
                    </div>
                    <?php if ($adTire->price_usd): ?>
                        <div class="card-descr-price-cur">
                            =<span class="dol"><?= $adTire->getPriceNormal(true) ?></span> y.e
                        </div>
                    <?php endif; ?>
                    <?= $adTire->bargain ? '<div class="card-descr-auction">Торг</div>' : '' ?>
                </div>
                <div class="card-descr-about clearfix">
                    <div class="card-descr-title">
                        <ul>
                            <li>Сезон</li>
                            <li>Производитель</li>
                            <li>Радиус</li>
                            <li>Профиль</li>
                            <li>Количество</li>
                            <li>Состояние</li>
                        </ul>
                    </div>
                    <div class="card-descr-values">
                        <ul>
                            <li class="season"><?= $adTire->seasonName ?></li>
                            <li class="mark"><?= $adTire->brand->name ?></li>
                            <li class="radius"><?= $adTire->radiusName ?></li>
                            <li class="size"><?= $adTire->size ?></li>
                            <li class="count"><?= $adTire->amountName ?></li>
                            <li class="state"><?= $adTire->isNewName ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="add-advert">
                <a href="#add-advert-pop-up">+ Добавить объявление</a>
            </div>
        </div>
        <div class="card-options">
            <div class="card-options-categories">
                <ul class="inset-tabs" role="tablist">
                    <li class="active"><a href="#card-option-descr" data-toggle="tab">Описание</a></li>
                </ul>
            </div>
            <div class="card-options-container">
                <div class="select-card-option active" id="card-option-descr">
                    <div class="about-auto">
                        <?= $adTire->description ?>
                    </div>
                </div>
            </div>
            <div class="card-options-status <?= AdHelper::getConditionColor($adTire->condition) ?>">Оценка состояния: <span class="status"><?= $adTire->condition ?></span> из 6</div>
        </div>
        <div class="card-contacts-mobile">
            <?= $this->render("../common/about-user-block-in-ad", ['user' => $adTire->user, 'ad' => $adTire]) ?>
            <div class="card-contacts-mobile-bottom">
                <div class="about-advert-item number-advert">№: <span><?= $adTire->id ?></span></div>
                <?php if (Yii::$app->user->can(Rbac::PERMISSION_USER)) : ?>
                    <div class="complain">
                        <a href="javascript:void(0)" class="complaint-link" data-ad-id="<?= $adTire->id ?>" data-ad-type="<?= AdCar::type() ?>">пожаловаться</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php  if ($adTire->status == Ad::STATUS_PREVIEW) : ?>
            <div class="add-block">
                <a href="<?= Url::to(['/main/tires/edit', 'id' => $adTire->id]) ?>" class="publish-add">Редактировать</a>
                <a href="<?= Url::to(['/main/tires/publish', 'id' => $adTire->id]) ?>" class="publish-add">Опубликовать объявление</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!--<?= UrgentSaleWidget::widget() ?>-->

<?= $this->render('@app/views/common/pop-messages', ['ad' => $adTire]) ?>