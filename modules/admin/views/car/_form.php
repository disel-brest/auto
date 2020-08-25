<?php

use app\helpers\AutoHelper;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use app\modules\main\models\CarOptions;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $carForm \app\modules\main\forms\AddCarForm */

$js = <<<JS
    (function() {
        window.addEventListener("load", function() {
            $("#addcarform-brand_id").on("change", function() {
                var contentBlock = $('#addcarform-model_id');
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
        });
    })();
JS;
$this->registerJs($js);

$form = ActiveForm::begin();

if (isset($carForm)) {
    echo $this->render("@app/modules/admin/views/common/new-user-form", ['userForm' => $userForm, 'form' => $form]);
}
?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <div class="container-fluid no-padding">
            <div class="row no-padding">
                <div class="col-md-6">
                    <?= $form->field($carForm, 'brand_id')
                        ->dropDownList(ArrayHelper::map(AutoBrand::find()->asArray()->all(), 'id', 'name'), ['prompt' => 'Выберите марку']) ?>

                    <?= $form->field($carForm, 'model_id')
                        ->dropDownList(ArrayHelper::map($carForm->brand_id ? AutoModel::find()->where(['brand_id' => $carForm->brand_id])->asArray()->all() : [], 'id', 'name')) ?>

                    <?= $form->field($carForm, 'year')->input('number') ?>

                    <?= $form->field($carForm, 'odometer')->input('number') ?>

                    <?= $form->field($carForm, 'body_style')->dropDownList(AutoHelper::bodyStylesList()) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($carForm, 'fuel_id')->dropDownList(AutoHelper::fuelTypesList()) ?>

                    <?= $form->field($carForm, 'engine_volume')->dropDownList(AutoHelper::engineVolumesList()) ?>

                    <?= $form->field($carForm, 'transmission')->dropDownList(AutoHelper::transmissionList()) ?>

                    <?= $form->field($carForm, 'drivetrain')->dropDownList(AutoHelper::drivetrainList()) ?>

                    <?= $form->field($carForm, 'color')->dropDownList(AutoHelper::colorList()) ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-lg-12">
                    <?= $form->field($carForm, 'options')->checkboxList(ArrayHelper::map(CarOptions::find()->where(['<>', 'parent_id', 0])->asArray()->all(), 'id', 'name')) ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-lg-12">
                    <?= $form->field($carForm, 'description')->textarea(['rows' => 6]) ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-sm-3">
                    <?= $form->field($carForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-3 no-padding">
                    <?= $form->field($carForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-2 no-padding">
                    <?= $form->field($carForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-2 no-padding">
                    <?= $form->field($carForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($carForm, 'photo[]')->fileInput() ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-lg-12">
                    <?= $form->field($carForm, 'price')->input('number') ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-sm-2">
                    <?= $form->field($carForm, 'bargain')->checkbox() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($carForm, 'change')->checkbox() ?>
                </div>
                <div class="col-sm-8">
                    <?= $form->field($carForm, 'law_firm')->checkbox() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(isset($carForm) ? 'Добавить объявление' : 'Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
