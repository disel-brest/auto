<?php

use app\modules\user\models\User;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $form \yii\bootstrap\ActiveForm */
/* @var $userForm \app\modules\admin\forms\NewUserForm */

?>
<div class="container-fluid no-padding">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Продавец</h3>
        </div>
        <div class="box-body table-responsive">
            <div class="col-md-6">
                <?= $form->field($userForm, 'phone_operator')->dropDownList(User::getPhoneOperatorsArray()) ?>
                <?= $form->field($userForm, 'phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($userForm, 'city')
                    ->widget(AutoComplete::className(), [
                        'clientOptions' => [
                            'source' => new JsExpression("function(request, response) {
                                    $.getJSON('/main/default/get-cities', {
                                        city: request.term
                                    }, response);
                                }")
                        ],
                        'options' => [
                            'class' => 'form-control'
                        ]
                    ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($userForm, 'username')->textInput(['maxlength' => true]) ?>
                <?= $form->field($userForm, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($userForm, 'avatar')->fileInput() ?>
            </div>
        </div>
    </div>
</div>
