<?php

use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use app\modules\user\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $partsForm \app\modules\main\forms\AddPartForm */

$this->title = 'Добавление объявлений по продаже запчастей';
$this->params['breadcrumbs'][] = ['label' => 'Запчасти', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Добавление объявлений';

$js = <<<JS
    (function() {
        window.addEventListener("load", function() {
            $("#addpartform-brand_id").on("change", function() {
                var contentBlock = $('#addpartform-model_id');
                var brandId = $(this).val();
                $.ajax({
                    url: '/main/default/get-models',
                    dataType: "json",
                    type: "POST",
                    data: {brand_id: brandId, _csrf: yii.getCsrfToken()},
                    beforeSend: function () {
                        contentBlock.empty();
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (data.result == 'success') {
                            var html = "";
                            $.each(data.models, function (key, model) {
                                html = html + '<option value="'+model.id+'">' + model.name + '</option>';
                            });
                            contentBlock.html(html);
                        } else if (message in data) {
                            alert(data.message);
                        } else {
                            alert("Какая-то ошибка");
                        }
                    },
                    error: function () {
                        alert("Ошибка при загрузке с сервера");
                    }
                });
            });
            
            var partsList = $("#parts-list");
            var partTmp = $("#part-template").find("> DIV");
            partTmp.clone().appendTo(partsList);
            $("#plus-part-button").on("click", function() {
                partTmp.clone().appendTo(partsList);
            });
        });
    })();
JS;
$this->registerJs($js);

?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-6 no-padding">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Данные по авто</h3>
                </div>
                <div class="box-body table-responsive">

                    <?= $form->field($partsForm, 'brand_id')
                        ->dropDownList(ArrayHelper::map(AutoBrand::find()->asArray()->all(), 'id', 'name'), ['prompt' => 'Выберите марку']) ?>

                    <?= $form->field($partsForm, 'model_id')
                        ->dropDownList(ArrayHelper::map($partsForm->brand_id ? AutoModel::find()->where(['brand_id' => $partsForm->brand_id])->asArray()->all() : [], 'id', 'name')) ?>

                    <?= $form->field($partsForm, 'fuel_id')->dropDownList(AutoHelper::fuelTypesList()) ?>

                    <?= $form->field($partsForm, 'engine_volume')->dropDownList(AutoHelper::engineVolumesList()) ?>

                    <?= $form->field($partsForm, 'year')->input('number') ?>

                    <?= $form->field($partsForm, 'body_style')->dropDownList(AutoHelper::bodyStylesList()) ?>

                </div>
            </div>
        </div>
        <div class="col-lg-6 no-padding">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Продавец</h3>
                </div>
                <div class="box-body table-responsive">

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

                    <?= $form->field($userForm, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($userForm, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($userForm, 'avatar')->fileInput() ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Запчасти</h3>
                <div class="box-tools">
                    <button class="btn btn-sm btn-primary btn-flat" id="plus-part-button" type="button">+</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="container-fluid no-padding" id="parts-list"></div>
            </div>
            <div class="box-footer">
                <?= Html::submitButton('Сохранить запчасти', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="hidden" id="part-template">
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($partsForm, 'category_id[]')->dropDownList(AdHelper::getCategoriesArray())->label(false) ?>
        </div>
        <div class="col-sm-2 no-padding">
            <?= $form->field($partsForm, 'name[]')->textInput(['maxlength' => true, 'placeholder' => 'Название'])->label(false) ?>
        </div>
        <div class="col-sm-5">
            <?= $form->field($partsForm, 'description[]')->textInput(['maxlength' => true, 'placeholder' => 'Описание'])->label(false) ?>
        </div>
        <div class="col-sm-1 no-padding">
            <?= $form->field($partsForm, 'price[]')->input('number', ['placeholder' => 'Цена, BYN'])->label(false) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($partsForm, 'photo[]')->fileInput(['placeholder' => 'Фото'])->label(false) ?>
        </div>
    </div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
