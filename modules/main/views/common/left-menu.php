<?php

use app\components\Cacher;
use yii\helpers\Url;

?>
<div class="userbar-wrap">
    <div class="userbar">
        <h2>Личный кабинет</h2>
        <ul>
            <li class="active"><a href="<?= Url::to(['/user/cabinet/index']) ?>">Мои объявления <span class="count-adds">(<?= Cacher::userAdCount(Yii::$app->user->id) ?>)</span></a></li>
            <li><a href="<?= Url::to(['/user/ad-message/index']) ?>">Мои сообщения <span class="count-sms">(0)</span></a></li>
            <!--<li><a href="<?= Url::to(['/user/default/logout']) ?>" data-method="POST">Выйти из профиля</a></li>-->
        </ul>
        <div class="add-advert-btn">
            <a href="#add-advert-pop-up">
                <span>Добавить объявление</span>
            </a>
        </div>
    </div>
</div>
<div class="content-left-btns">
    <div class="select-group-radius">
        <select name="select-category">
            <option value="">АВТО</option>
            <option value="">Прочее...</option>
        </select>
    </div>
    <div class="select-group-radius">
        <select name="select-subcategory" class="lk-category-dropdown">
            <!-- <option value="<?= Url::to(['/main/cars/add']) ?>"<?= Yii::$app->controller->id == 'cars' ? ' selected' : '' ?>>Легковые авто</option> -->
            <option value="<?= Url::to(['/main/parts/add']) ?>"<?= Yii::$app->controller->id == 'parts' ? ' selected' : '' ?>>Автозапчасти</option>
            <option value="<?= Url::to(['/main/tires/add']) ?>"<?= Yii::$app->controller->id == 'tires' ? ' selected' : '' ?>>Шины</option>
            <option value="<?= Url::to(['/main/wheels/add']) ?>"<?= Yii::$app->controller->id == 'wheels' ? ' selected' : '' ?>>Диски</option>
        </select>
    </div>
</div>
