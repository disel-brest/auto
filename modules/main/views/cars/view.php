<?php
use app\modules\main\models\Ad;
use app\modules\main\widgets\UrgentSaleWidget;
use app\rbac\Rbac;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $adCar \app\modules\main\models\AdCar */

?>
<div class="content-left">
    <div class="about-advert">
        <div class="count-views">Количество просмотров: <span><?= $adCar->views ?></span></div>
        <div class="number-advert">Номер объявления: <span><?= $adCar->id ?></span></div>
        <div class="data-create">Дата подачи объявления: <span><?= Yii::$app->formatter->asDate($adCar->created_at) ?></span></div>
        <?php if (Yii::$app->user->can(Rbac::PERMISSION_USER)) : ?>
            <div class="complain">
                <a href="javascript:void(0)" class="complaint-link" data-ad-id="<?= $adCar->id ?>" data-ad-type="<?= Ad::TYPE_CAR ?>">пожаловаться</a>
            </div>
        <?php endif; ?>
    </div>

    <?= $this->render("../common/about-user-block-in-ad", ['user' => $adCar->user, 'ad' => $adCar]) ?>

</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="/">Объявления</a> > <a href="<?= Url::to(['/main/cars/index']) ?>">Продажа транспорта</a> > <a href="<?= Url::to(['/main/cars/index']) ?>">Продажа легковых авто</a>
    </div>
    <div class="card">
        <div class="card-title">
            <h2><span class="mark"> <?= $adCar->brand->name ?> </span><span class="model"> <?= $adCar->model->name ?> </span><span class="engine"> <?= $adCar->engineVolume ?> <?= $adCar->getFuelName(true) ?></span><span class="year"> <?= $adCar->year ?> г.в.</span></h2>
        </div>
        <div class="card-content">
            <div class="card-gallery">
                <?php if ($adCar->hasPhoto() && count($adCar->photos) > 1) : ?>
                <div class="gallery-items">
                    <?php foreach ($adCar->photos as $photo) : ?>
                        <div class="card-gallery-item"></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="fotorama"  data-thumbheight="73px" data-thumbwidth="105px"  data-nav="thumbs" data-allowfullscreen="true">
                    <?php if ($adCar->hasPhoto()) : ?>
                        <?php foreach ($adCar->photos as $photo) : ?>
                            <a href="<?= $adCar->getFilesPath(true) . "/" . $photo ?>">
                                <img src="<?= $adCar->getFilesPath(true) . "/" . $photo ?>" alt="">
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-descr">
                <div class="card-descr-price">
                    <div class="card-descr-price-blr">
                        <?= $adCar->priceNormal ?> <span>бел.руб</span>
                    </div>
                    <div class="card-descr-price-cur">
                        =<span class="dol"><?= $adCar->getPriceNormal(true) ?></span> y.e
                    </div>
                </div>
                <div class="card-descr-about">
                    <div class="card-descr-title">
                        <ul>
                            <li>Год выпуска</li>
                            <li>Двигатель</li>
                            <li>Пробег</li>
                            <li>КПП</li>
                            <li>Кузов</li>
                            <li>Цвет</li>
                            <li>Привод</li>
                        </ul>
                    </div>
                    <div class="card-descr-values">
                        <ul>
                            <li class="year"><?= $adCar->year ?> г.</li>
                            <li class="engine"><?= $adCar->engineVolume ?> <?= $adCar->getFuelName(true) ?></li>
                            <li class="km"><?= $adCar->odometerNormalize ?></li>
                            <li class="trans"><?= $adCar->transmissionName ?></li>
                            <li class="body"><?= $adCar->bodyStyle ?></li>
                            <li class="color"><?= $adCar->colorName ?></li>
                            <li class="kv"><?= $adCar->drivetrainName ?></li>
                        </ul>
                    </div>
                </div>
                <div class="card-descr-actions">
                    <?= $adCar->bargain ? '<div class="card-descr-auction">Торг</div>' : '' ?>
                    <?= $adCar->change ? '<div class="card-descr-exchange">Обмен</div>' : '' ?>
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
                        <?= $adCar->description ?>
                    </div>
                    <div class="complectation">
                        <p>Комплектация</p>
                        <ul>
                            <?php foreach ($adCar->options as $carOption) : ?>
                                <li><?= $carOption->name ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php  if ($adCar->status == Ad::STATUS_PREVIEW) : ?>
        <div class="add-block">
            <a href="<?= Url::to(['/main/cars/edit', 'id' => $adCar->id]) ?>" class="publish-add">Редактировать</a>
            <a href="<?= Url::to(['/main/cars/publish', 'id' => $adCar->id]) ?>" class="publish-add">Опубликовать объявление</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= UrgentSaleWidget::widget() ?>

<?= $this->render('@app/views/common/pop-messages', ['ad' => $adCar]) ?>