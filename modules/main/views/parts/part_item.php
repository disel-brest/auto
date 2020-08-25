<?php

use app\helpers\PluralForm;
use yii\helpers\Url;

/* @var $adPart \app\modules\main\models\AdPart */

?>
<div class="group-adverts-item clearfix more-info">
    <div class="more-info-img gallery">
        <?php if ($adPart->photo) {
            ?>
            <a class="gallery-item" href="<?= $adPart->photoUrl ?>">
                <img src="<?= $adPart->photoUrl ?>" alt="<?= $adPart->name ?>">
            </a>
            <?php
        } else {
            echo "Нет фото";
        } ?>
    </div>
    <div class="more-info-detail-and-descr">
        <div class="more-info-descr"><?= $adPart->description ?></div>
        <div class="more-info-detail">
            <div class="counts-views">
                Кол-во просмотров: <span class="count"><?= $adPart->views ?></span>
            </div>
            <div class="number-add">Объявление № <span class="number"><?= $adPart->id ?></span></div>
            <a href="#" class="complaim">Пожаловаться</a>
        </div>
    </div>
    <div class="more-info-contact">
        <div class="more-info-contact-price">
            <p>Цена</p>
            <p class="price-part"><?= $adPart->price ?> руб</p>
        </div>
        <div class="more-info-contact-phone">
            <div class="show-phone-link" data-ad-id="<?= $adPart->id ?>" data-ad-type="<?= $adPart->type() ?>">Показать телефон</div>
            <!--<p><?= $adPart->user->phone_operator ?></p>
                                        <p class="phone-part"><?= $adPart->user->phone ?></p>-->
        </div>
        <div class="more-info-contact-time">
            <p>Звонить с <?= $adPart->user->callTimeFrom ?> до <?= $adPart->user->callTimeTo ?></p>
        </div>
    </div>
</div>