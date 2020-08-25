<?php

use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\TireBrand;
use app\modules\main\models\TireModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $tireForm \app\modules\main\forms\AddTireForm */

$js = <<<JS
    (function() {
        window.addEventListener("load", function() {
            $("#addtireform-brand_id").on("change", function() {
                var contentBlock = $('#addtireform-model_id');
                var brandId = $(this).val();
                $.ajax({
                    url: '/main/tires/get-models',
                    dataType: "json",
                    type: "POST",
                    data: {id: brandId, _csrf: yii.getCsrfToken()},
                    beforeSend: function () {
                        contentBlock.empty();
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (data.result == 'success') {
                            var html = "";
                            $.each(data.items, function (key, model) {
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

if (isset($tireForm)) {
    echo $this->render("@app/modules/admin/views/common/new-user-form", ['userForm' => $userForm, 'form' => $form]);
}

?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <div class="container-fluid no-padding">
            <div class="row no-padding">
                <div class="col-md-6">
                    <?= $form->field($tireForm, 'brand_id')
                        ->dropDownList(ArrayHelper::map(TireBrand::find()->asArray()->all(), 'id', 'name'), ['prompt' => 'Выберите марку']) ?>

                    <?= $form->field($tireForm, 'model_id')
                        ->dropDownList(ArrayHelper::map($tireForm->brand_id ? TireModel::find()->where(['brand_id' => $tireForm->brand_id])->asArray()->all() : [], 'id', 'name')) ?>

                    <?= $form->field($tireForm, 'tire_type')->radioList(AutoHelper::getTireTypesArray()) ?>

                    <?= $form->field($tireForm, 'season')->radioList(AutoHelper::getTireSeasonsArray()) ?>

                    <?= $form->field($tireForm, 'amount')->radioList(AutoHelper::getTireAmountArray()) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($tireForm, 'radius')->dropDownList(AutoHelper::getTireRadiusArray()) ?>

                    <?= $form->field($tireForm, 'width')->dropDownList(AutoHelper::getTireWidthArray()) ?>

                    <?= $form->field($tireForm, 'aspect_ratio')->dropDownList(AutoHelper::getTireAspectRatioArray()) ?>

                    <?= $form->field($tireForm, 'condition')->dropDownList(AutoHelper::getTireConditionArray()) ?>

                    <?= $form->field($tireForm, 'price')->input('number') ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-lg-12">
                    <?= $form->field($tireForm, 'description')->textarea(['rows' => 6]) ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="col-sm-3 col-md-2">
                    <?= $form->field($tireForm, 'is_new')->checkbox() ?>
                </div>
                <div class="col-sm-9 col-md-10">
                    <?= $form->field($tireForm, 'bargain')->checkbox() ?>
                </div>
            </div>
            <div class="row no-padding">

                <div class="add-block">
                    <div class="wrap">
                        <div class="add-photos">
                            <?php PhotoHelper::renderPhotosForm(\app\modules\main\models\Ad::TYPE_TIRE, $tireForm->saved_photos, $tireForm) ?>
                        </div>
                    </div>
                </div>

                <?php /*
                <div class="col-sm-3">
                    <?= $form->field($tireForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-3 no-padding">
                    <?= $form->field($tireForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-2 no-padding">
                    <?= $form->field($tireForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-2 no-padding">
                    <?= $form->field($tireForm, 'photo[]')->fileInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($tireForm, 'photo[]')->fileInput() ?>
                </div>
                */ ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(isset($tireForm) ? 'Добавить объявление' : 'Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>