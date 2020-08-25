<?php

use app\board\repositories\CityRepository;
use app\modules\user\models\User;
use yii\bootstrap\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $user User */

?>
<div class="userblock">

        <div class="userblock-about">
            <div class="userblock-about-photo">
                <div class="userblock-about-img">
                     <img src="<?= $user->avatarUrl ?>" height="38" width="39" alt="">
                </div>
            </div>
            <input type="file" name="user-photo" class="user-photo" id="user-photo-input">
            <div class="userblock-about-name">
                <input type="text" name="user-name" placeholder="Введите имя" id="cab-username-input" value="<?= $user->username ?>">
            </div>
        </div>
        <div class="userblock-contacts">
            <div class="userblock-contacts-city">
                <span>Мой город</span>
                <div class="select-large-radius ib">
                    <?php /*= AutoComplete::widget([
                        'name' => 'city',
                        'id' => 'username-city',
                        'value' => $user->city_id ? $user->city->name : null,
                        'clientOptions' => [
                            'source' => new JsExpression("function(request, response) {
                                    $.getJSON('/main/default/get-cities', {
                                        city: request.term
                                    }, response);
                                }")
                        ],
                        'options' => [
                            'placeholder' => 'Укажите город',
                        ]
                    ])*/ ?>
                    <?= Html::dropDownList('city', $user->city_id, CityRepository::getCitiesByRegion(Yii::$app->params['region']), ['id' => 'username-city']) ?>
                </div>
            </div>
            <div class="userblock-contacts-phone">
                <span>Мой телефон</span>
                <div class="large-radius ib">
                    <div class="select-small ib">
                        <?= Html::dropDownList('select-operator-user', $user->phone_operator, User::getPhoneOperatorsArray(), [
                            'id' => 'user-phone_operator'
                        ]) ?>
                    </div>
                    <div class="input-middle ib">
                        <input type="text" name="user-phone" value="<?= $user->phone ?>" placeholder="+375 29 808-88-80" id="user-phone">
                    </div>
                </div>
            </div>
            <div class="userblock-contacts-time top">
                <span>Время звонка</span>
                <div class="select-small-radius ib">
                    <?= Html::dropDownList('select-fromtime', $user->callTime['from'], User::getCallTimeArray(), [
                        'id' => 'user-calltime-from'
                    ]) ?>
                </div>
                <span>-</span>
                <div class="select-small-radius ib">
                    <?= Html::dropDownList('select-tilltime', $user->callTime['to'], User::getCallTimeArray(), [
                        'id' => 'user-calltime-to'
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="userblock-mail">
            <span><?= $user->email ?></span>
            <p>если забудете пароль, мы вышлем Вам его на почту</p>
            <a class="userblock-password" href="#password-change-pop-up">изменить пароль</a>
        </div>

</div>