<?php

use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $part app\modules\main\models\AdPart */
/* @var $model \app\modules\main\forms\PartForm */

$this->title = 'Редактирование запчасти: ' . $part->name;
$this->params['breadcrumbs'][] = ['label' => 'Запчасти', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $part->name, 'url' => ['view', 'id' => $part->id]];
$this->params['breadcrumbs'][] = 'Редактирование';

$js = <<<JS
    (function() {
        window.addEventListener("load", function() {
            $("#partform-brand_id").on("change", function() {
                var contentBlock = $('#partform-model_id');
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

?>
<div class="ad-part-update">
    <div class="ad-part-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'brand_id')->dropDownList(ArrayHelper::map(AutoBrand::find()->asArray()->all(), 'id', 'name')) ?>

        <?= $form->field($model, 'model_id')->dropDownList(ArrayHelper::map($model->brand_id ? AutoModel::find()->where(['brand_id' => $model->brand_id])->asArray()->all() : [], 'id', 'name')) ?>

        <?= $form->field($model, 'fuel_id')->dropDownList(AutoHelper::fuelTypesList()) ?>

        <?= $form->field($model, 'engine_volume')->dropDownList(AutoHelper::engineVolumesList()) ?>

        <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'body_style')->dropDownList(AutoHelper::bodyStylesList()) ?>

        <?= $form->field($model, 'category_id')->dropDownList(AdHelper::getCategoriesArray()) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 7]) ?>

        <?= $form->field($model, 'price')->input('number') ?>

        <?= $form->field($model, 'photoUpload')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton($part->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $part->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
