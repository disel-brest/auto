<?php

use app\board\repositories\CityRepository;
use app\modules\user\models\User;
use yii\helpers\Html;

/* @var $form \yii\bootstrap\ActiveForm */
/* @var $model \app\modules\main\forms\AddCarForm|\app\modules\main\forms\AddPartForm */

?>
<div class="userbar-bottom">
    <div class="wrap">
        <span class="userbar-bottom-title ib">Ваши контактные данные</span>
            <div class="select-large-radius ib">
                <?= Html::activeDropDownList($model, 'city', CityRepository::getCitiesByRegion(Yii::$app->params['region'])) ?>
            </div>
             <?php /*= $form->field($model, 'city', ['options' => ['class' => 'input-large-poluradius ib']])
                ->label(false)
                ->widget(AutoComplete::className(), [
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
                ]) */?>
        <div class="large-radius ib">
            <?= $form->field($model, 'phone_operator', ['options' => ['class' => 'select-small ib']])
                ->label(false)
                ->dropDownList(User::getPhoneOperatorsArray()) ?>
            <?= $form->field($model, 'phone', ['options' => ['class' => 'input-middle ib']])
                ->label(false)
                ->textInput(['placeholder' => '+375 29 808-88-80'])?>
        </div>
        <div class="userblock-contacts-time ib">
            <span class="userbar-bottom-title ib">Время звонка</span>
            <span>с</span>
            <?= $form->field($model, 'call_time_from', ['options' => ['class' => 'select-small-radius ib']])
                ->label(false)
                ->dropDownList(User::getCallTimeArray()) ?>
            <span>до</span>
            <?= $form->field($model, 'call_time_to', ['options' => ['class' => 'select-small-radius ib']])
                ->label(false)
                ->dropDownList(User::getCallTimeArray()) ?>
        </div>
    </div>
</div>