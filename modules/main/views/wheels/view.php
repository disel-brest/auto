<?php

use app\board\helpers\PhotoHelper;
use app\helpers\AdHelper;
use app\helpers\PluralForm;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdWheel;
use app\modules\main\widgets\UrgentSaleWidget;
use app\rbac\Rbac;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $adWheel AdWheel */

?>
<div class="content-left">
    <div class="about-advert">
        <div class="about-advert-item count-views">Кол-во просмотров: <span><?= $adWheel->views ?></span></div>
        <div class="about-advert-item number-advert">№ объявления: <span><?= $adWheel->id ?></span></div>
        <div class="about-advert-item data-create">Дата подачи объявления: <span><?= Yii::$app->formatter->asDate($adWheel->created_at) ?></span></div>
        <?php if (Yii::$app->user->can(Rbac::PERMISSION_USER)) : ?>
            <div class="complain">
                <a href="javascript:void(0)" class="complaint-link" data-ad-id="<?= $adWheel->id ?>" data-ad-type="<?= AdWheel::type() ?>">пожаловаться</a>
            </div>
        <?php endif; ?>
    </div>

    <?= $this->render("../common/about-user-block-in-ad", ['user' => $adWheel->user, 'ad' => $adWheel]) ?>

</div>
<div class="content-right inner">
    <div class="breadcrumbs">
        <a href="/">Главная</a> > <a href="<?= Url::to(['/main/wheels/index']) ?>">Диски</a> > <a href="javascript:void(0);"><?= $adWheel->getFullName() ?></a>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="card-title">
                <h2>
                    <span class="type"> <?= $adWheel->wheelTypeName ?> диски </span>
                    <span class="mark"> <?= $adWheel->firm ?> </span>
                    <span class="radius"> <?= $adWheel->radiusName ?> </span>
                </h2>
            </div>
            <div class="card-title-mobile">
                <div class="card-title-mobile-date">Дата подачи объявления: <span><?= Yii::$app->formatter->asDate($adWheel->created_at) ?></span></div>
                <div class="card-title-mobile-views"><?= $adWheel->views ?></div>
            </div>
            <div class="card-gallery">
                <?php if ($adWheel->hasPhoto()) : ?>
                    <div class="status <?= AdHelper::getConditionColor($adWheel->condition) ?>"><?= $adWheel->condition ?></div>
                    <?php if (count($adWheel->photos) > 0) : ?>
                        <div class="gallery-items">
                            <?php foreach ($adWheel->photos as $photo) : ?>
                                <div class="card-gallery-item">
                                    <?php if (count($adWheel->photos) == 1): ?>
                                        <img src="<?= $adWheel->mainPhoto ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="fotorama"  data-thumbheight="84px" data-thumbwidth="114px"  data-nav="thumbs" data-allowfullscreen="true">
                    <?php if ($adWheel->hasPhoto()) : ?>
                        <?php foreach ($adWheel->photos as $photo) : ?>
                            <a href="<?= $adWheel->getFilesPath(true) . "/" . PhotoHelper::getNameFor($photo, PhotoHelper::TYPE_MN) ?>">
                                <img src="<?= $adWheel->getFilesPath(true) . "/" . PhotoHelper::getNameFor($photo, PhotoHelper::TYPE_MN) ?>" alt="">
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-descr">
                <div class="card-descr-price">
                    <div class="card-descr-price-blr">
                        <?= $adWheel->priceNormal ?> <span>руб.</span>
                    </div>
                    <?php if ($adWheel->price_usd): ?>
                        <div class="card-descr-price-cur">
                            =<span class="dol"><?= $adWheel->getPriceNormal(true) ?></span> y.e
                        </div>
                    <?php endif; ?>
                    <?= $adWheel->bargain ? '<div class="card-descr-auction">Торг</div>' : '' ?>
                </div>
                <div class="card-descr-about clearfix">
                    <div class="card-descr-title">
                        <ul>
                            <li>Тип дисков</li>
                            <li>Радиус</li>
                            <li>Кол-во болтов</li>
                            <li>Кол-во дисков</li>
                            <li>Для марки</li>
                            <li>Состояние</li>
                        </ul>
                    </div>
                    <div class="card-descr-values">
                        <ul>
                            <li class="type"><?= $adWheel->wheelTypeName ?></li>
                            <li class="radius"><?= $adWheel->radiusName ?></li>
                            <li class="count-boltes"><?= PluralForm::get($adWheel->bolts, 'болт', 'болта', 'болтов') ?></li>
                            <li class="count-wheel"><?= $adWheel->amountName ?></li>
                            <li class="mark"><?= $adWheel->auto_brand_id ? $adWheel->autoBrand->name : '-' ?></li>
                            <li class=""><?= $adWheel->isNewName ?></li>
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
                        <?= $adWheel->description ?>
                    </div>
                </div>
            </div>
            <div class="card-options-status <?= AdHelper::getConditionColor($adWheel->condition) ?>">Оценка состояния: <span class="status"><?= $adWheel->condition ?></span> из 6</div>
        </div>
        <div class="card-contacts-mobile">
            <?= $this->render("../common/about-user-block-in-ad", ['user' => $adWheel->user, 'ad' => $adWheel]) ?>
            <div class="card-contacts-mobile-bottom">
                <div class="about-advert-item number-advert">№: <span><?= $adWheel->id ?></span></div>
                <?php if (Yii::$app->user->can(Rbac::PERMISSION_USER)) : ?>
                    <div class="complain">
                        <a href="javascript:void(0)" class="complaint-link" data-ad-id="<?= $adWheel->id ?>" data-ad-type="<?= AdWheel::type() ?>">пожаловаться</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php  if ($adWheel->status == Ad::STATUS_PREVIEW) : ?>
            <div class="add-block">
                <a href="<?= Url::to(['/main/wheels/edit', 'id' => $adWheel->id]) ?>" class="publish-add">Редактировать</a>
                <a href="<?= Url::to(['/main/wheels/publish', 'id' => $adWheel->id]) ?>" class="publish-add">Опубликовать объявление</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!--<?= UrgentSaleWidget::widget() ?>-->

<?= $this->render('@app/views/common/pop-messages', ['ad' => $adWheel]) ?>