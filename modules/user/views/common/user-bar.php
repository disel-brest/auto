<?php

use app\components\Cacher;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<div class="userbar-wrap">
   <div class="userbar">
        <h2>Личный кабинет</h2>
        <ul>
            <li class="active"><a href="<?= Url::to(['/user/cabinet/index']) ?>">Мои объявления <span class="count-adds">(<?= Cacher::userAdCount(Yii::$app->user->id) ?>)</span></a></li>
            <li><a href="<?= Url::to(['/user/ad-message/index']) ?>">Мои сообщения <span class="count-sms">(0)</span></a></li>
            <!--<li><?= Html::a("Выйти из профиля", ['/user/default/logout'], ['data' => ['method' => 'post']]) ?></li>-->
        </ul>
        <div class="add-advert-btn">
            <a href="#add-advert-pop-up">
                <span>Добавить объявление</span>
            </a>
        </div>
    </div>
</div>
