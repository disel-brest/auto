<?php

/* @var $user \app\modules\user\models\User */
/* @var $ad \app\modules\main\models\Ad */

?>
<div class="about-user">
    <div class="about-user-title">Информация о продавце:</div> 
    <div class="about-user-line">
        <div class="photo-user">
            <img src="<?= $user->avatarUrl ?>" alt="">
        </div>
    </div>
    <div class="about-user-line">
        <div class="category-user"><?= isset($ad->law_firm) ? "Юр. фирма" : "Частное лицо" ?></div>
    </div>
    <div class="about-user-line">
        <div class="city-user">г. <?= $user->city->name ?></div>    
    </div>
    <div class="about-user-line">
        <div class="phone-operator"><?= $user->phone_operator ?></div>
        <div class="phone-user"><?= $user->phone ?></div>
    </div>
    <a class="phone-btn" href="tel:<?= $user->phone ?>">Позвонить</a>
    <div class="user-time">Звонить с <?= $user->callTimeFrom ?> до <?= $user->callTimeTo ?></div>
    <?php if (!Yii::$app->user->isGuest && $user->id != Yii::$app->user->id) : ?>
        <div class="contact-with-user">Написать сообщение</div>
    <?php endif; ?>
</div>