<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\components\Cacher;
use app\helpers\MessageHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $this->title ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= Html::csrfMetaTags() ?>
    <?= Html::cssFile((YII_DEBUG ? '@web/css/all.css' : '@web/css/all.min.css') . '?v=' . filemtime(Yii::getAlias(YII_DEBUG ? '@webroot/css/all.css' : '@webroot/css/all.min.css'))) ?>
    <link rel="shortcut icon" href="/favicon.ico">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/js/moment/moment.js"></script>
    <script src="/js/moment/locale/ru.js"></script>
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrapper">
    <div class="container">
        <div class="header-wrap">
            <header>
                <div class="header-left">
                    <div class="logo">
                        <a href="/"><!--<?= Yii::$app->name ?>-->
                            <div class="logo-img">
                                <img src="/new-images/logo.png" alt="">
                            </div>
                            <div class="logo-title">
                                <div class="logo-title-top">Brest.by</div>
                                <div class="logo-title-bottom">портал Бреста</div>
                            </div>
                        </a>
                    </div>
                    <ul class="nav-mobile">
                        <li></li>
                        <li></li>    
                        <li></li>    
                    </ul>
                    <nav>
                        <ul class="menu">
                            <li><a href="#">Карта авторынка</a></li>
                            <li><a href="#">Запчасти</a></li>                        
                        </ul>
                        <div class="menu-mobile">
                            <a href="#add-advert-pop-up" class="menu-mobile-btn">Добавить объявления</a>
                            <ul class="menu-mobile-list">
                             <li class="menu-mobile-item">
                                 <a href="<?= Url::to(['/main/parts/index']) ?>">Автозапчасти б/у</a>
                             </li>
                             <li class="menu-mobile-item">
                                 <a href="<?= Url::to(['/main/wheels/index']) ?>">Диски</a>
                             </li>
                             <li class="menu-mobile-item">
                                 <a href="<?= Url::to(['/main/tires/index']) ?>">Шины</a>
                             </li>
                            </ul>
                            <div class="group-content-img"><a href="#"></a></div>
                        </div>
                    </nav>
                    <div class="add-advert-icon">
                        <a href="#add-advert-pop-up">Добавить объявления<span>+</span></a>
                    </div>
                    <div class="search">
                        <div class="search-icon"></div>
                        <div class="search-block">
                            <form method="post" id="search">
                                <input type="text" name="search">
                                <input type="submit">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <?php
                    if (Yii::$app->user->isGuest) {
                        ?>
                        <div class="sign-links">
                            <span class="sign-in-icon"></span>
                            <a class="sign-in-link" href="#sign-in-pop-up">Войти</a><a class="reg-in-link" href="#reg-in-pop-up">Регистрация</a>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="sign-links">
                            <div class="user-in">
                                <div
                                    class="user-in-icon"
                                    <?= Yii::$app->user->identity->avatar ? 'style="background-image:url(' . Yii::$app->user->identity->avatarUrl . ');background-size:40px 40px;"' : '' ?>
                                ></div>
                                <div class="user-in-title">Личный кабинет<!-- <span class="new-msg">1</span> --></div>
                            </div>
                            <div class="user-in-drop">
                                <button class="user-in-drop-close"></button>
                                <div class="user-in-drop-title">Личный кабинет</div>
                                <ul>
                                    <li><a href="<?= Url::to(['/user/cabinet/index']) ?>">Мои объявления<span class="count"><?= Cacher::userAdCount(Yii::$app->user->id) ?></span></a></li>
                                    <li><a href="<?= Url::to(['/user/ad-message/index']) ?>">Мои сообщения <?= MessageHelper::getNewMessagesCount() ?></a></li>
                                    <li><a href="#">Настройка профиля</a></li>
                                    <!-- <li><a href="<?= Url::to(['/main/services/auto/auto-service/index']) ?>">Автосервисы</a></li> -->
                                </ul>
                                <div class="sign-out">
                                    <?= Html::a(
                                        'Выйти из профиля',
                                        '/logout',
                                        ['data-method' => 'post']
                                    ) ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </header>
            <div class="dropmenu">
                <ul class="submenu">
                    <!-- <li><a href="<?= Url::to(['/main/cars/index']) ?>">Легковые авто</a></li> -->
                    <!-- <li><a href="<?= Url::to(['/main/services/auto/auto-service/index']) ?>">Автосервисы</a></li> -->
                    <li class="submenu-item">
                        <a href="<?= Url::to(['/main/parts/index']) ?>">
                            <div class="submenu-item-img"></div>
                            <div class="submenu-item-text">Автозапчасти б/у</div>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="<?= Url::to(['/main/tires/index']) ?>">
                            <div class="submenu-item-img"></div>
                            <div class="submenu-item-text">Шины</div>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="<?= Url::to(['/main/wheels/index']) ?>">
                            <div class="submenu-item-img"></div>
                            <div class="submenu-item-text">Диски</div>
                        </a>
                    </li>
                </ul>
                <div class="dropmenu-bottom">
                    <span class="dropmenu-bottom-text">Продать свои запчасти</span>
                    <a href="#add-advert-pop-up" class="dropmenu-bottom-btn">Добавить объявления</a>
                </div>
            </div>
        </div>
        
        <div class="content">
            <?= $content ?>
        </div>


        <footer>
            <div class="footer-content">
                <div class="footer-left">
                    <div class="footer-logo">
                        <a href="/"><!--<?= Yii::$app->name ?>-->
                            <div class="logo-img">
                                <img src="/new-images/logo-footer.png" alt="">
                            </div>
                            <div class="logo-title">
                                <div class="logo-title-top">Brest.by</div>
                                <div class="logo-title-bottom">портал Бреста</div>
                            </div>
                        </a>
                    </div>
                    <p><a href="#">О проекте</a></p>
                    <p><a href="#">Обратная связь</a></p>
                </div>
                <div class="footer-section">
                    <div class="footer-section-link">
                        <a href="#">Интерактивная карта рынка автозапчастей в Бресте</a>
                    </div>
                </div>
                <div class="footer-section">
                    <div class="footer-section-link">
                        <a href="#">Автозапчасти Б/У</a>
                    </div>
                    <div class="footer-section-link">
                        <a href="#">Шины</a>
                    </div>
                    <div class="footer-section-link">
                        <a href="#">Диски</a>
                    </div>
                </div>
                <div class="footer-section">
                    <div class="footer-section-link">
                        <a href="#">Автосервисы</a>
                    </div>
                </div>
                <div class="footer-right">
                    <div class="add-advert-icon">
                        <a href="#add-advert-pop-up">Добавить объявления<span>+</span></a>
                    </div>
                    <div class="copyright"><span>&copy;</span> 2018</div>
                </div>
            </div>
        </footer>



    </div>
</div>

<?php if (Yii::$app->user->isGuest) {
    echo $this->render('@app/views/common/auth-popups.php');
} else {
    ?>
    <div id="password-change-pop-up" class="mfp-hide popup-wrap">
        <p class="popup-title">Изменить пароль</p>
        <?php ActiveForm::begin([
            'action' => '/user/cabinet/set-new-password',
            'id' => 'password-change'
        ]) ?>
            <label>
                Введите текущий пароль
            </label>
            <input type="password" name="oldPassword">
            <label>
                Введите новый пароль
            </label>
            <input type="password" name="newPassword">
            <label>
                Введите повторно новый пароль
            </label>
            <input type="password" name="newPasswordVerify">
            <input class="btn-confirm" type="button" value="Подтвердить" id="password-change-btn" href="#password-change-msg-pop-up">
        <?php ActiveForm::end() ?>
    </div>

    <div id="password-change-msg-pop-up" class="mfp-hide popup-wrap popup-msg">
        <p>Пароль успешно изменен!</p>
    </div>
    <?php
} ?>

<div id="add-advert-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Добавить объявление</p>
    <!-- <a class="add-advert-link" href="<?= Url::to(['/main/cars/add']) ?>">+ Продать легковой авто</a> -->
    <a class="add-advert-link" href="<?= Url::to(['/main/parts/add']) ?>">продать б/у автозапчасти</a>
    <a class="add-advert-link" href="<?= Url::to(['/main/tires/add']) ?>">продать шины</a>
    <a class="add-advert-link" href="<?= Url::to(['/main/wheels/add']) ?>">продать диски</a>
    <div class="popup-wrap-close"></div>
</div>

<?= $this->render('@app/views/common/brands-popup') ?>

<div id="select-model-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Выберите модель</p>
    <div class="select_model_pop-up_content"></div>
    <div class="popup-footer">
        <button id="all_models_btn" class="show-all-btn">Все модели</button>
    </div>
</div>

<div id="loader-pop-up" class="mfp-hide">
    <div class="center-block">
        <img src="/images/ajax-loader3.gif">
    </div>
</div>

<div id="alert-pop-up" class="mfp-hide">
    <div class="center-block">
        <p class="alert-message"></p>
    </div>
</div>

<!--Вслывающее окно Пожаловаться-->
<div class="popup_msg mfp-hide" id="complain-msg">
    <div class="popup_msg-wrap">
        <form id="complain-msg-form">
            <input type="hidden" name="ad_id">
            <input type="hidden" name="ad_type">
            <span class="popup-msg_title">
                Пожаловаться на объявление
            </span>
            <div class="popup-msg-text">
                <textarea name="msg-text" required></textarea>
            </div>
            <button type="button" class="popup-msg_btn" id="complain-msg-btn">Отправить</button>
        </form>
    </div>
    <div class="popup-success">
        Ваша жалоба отправлена
    </div>
</div>

<span class="hidden" id="ccId"><?= Yii::$app->controller->id ?></span>
<span class="hidden" id="acId"><?= Yii::$app->controller->action->id ?></span>

<?php //= Html::jsFile(YII_DEBUG ? '@web/js/lib.js' : '@web/js/lib.min.js?v=' . filemtime(Yii::getAlias('@webroot/js/lib.min.js'))) ?>
<?= Html::jsFile((YII_DEBUG ? '@web/js/all.js' : '@web/js/all.min.js') . '?v=' . filemtime(Yii::getAlias(YII_DEBUG ? '@webroot/js/all.js' : '@webroot/js/all.min.js'))) ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>