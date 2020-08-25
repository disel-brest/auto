<?php

use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\helpers\FormHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\TireBrand;
use app\modules\main\models\TireModel;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\modules\main\forms\AddTireForm */

$ue_course = Yii::$app->currency->getExchangeValue();
$js = <<<JS
    (function() {
        var ue_course = {$ue_course};
        $("#addtireform-price").on("change keyup", function() {
            var in_ue = Math.round(($(this).val() * ue_course) * 100) / 100;
            $("#price_in_ue").text(in_ue);
        });
        
        $("#addtireform-brand_id").on("change", function() {
            loadModels($(this).val());
        });
        
        $('#add-tire').on('click', '.publish-add', function () {
            $("#is-preview-input").val(0);
            if (!AutoBrest.photoValidate()) {
                return;
            }
            $("#add-tire").submit();
            
            var radius = $('#addtireform-radius');
            var radiusBlock = radius.closest('.linked-group-block');
            var radiusError = radius.parent().parent().find('.help-block-error');

            var width = $('#addtireform-width');
            var widthBlock = width.closest('.linked-group-block');
            var widthError = width.parent().parent().find('.help-block-error');

            var aspectRatio = $('#addtireform-aspect_ratio');
            var aspectRatioBlock = aspectRatio.closest('.linked-group-block');
            var aspectRatioError = aspectRatio.parent().parent().find('.help-block-error');

            if (!radius.val()) {
                radiusBlock.addClass('has-error');
                radiusError.text('Укажите радиус');
            }
            if (!width.val()) {
                widthBlock.addClass('has-error');
                widthError.text('Укажите ширину');
            }
            if (!aspectRatio.val()) {
                aspectRatioBlock.addClass('has-error');
                aspectRatioError.text('Укажите высоту');
            }
            radius.on('change', function() {
                radiusBlock.removeClass('has-error');
                radiusError.text('');                
            });
            width.on('change', function() {
                widthBlock.removeClass('has-error');
                widthError.text('');      
            });
            aspectRatio.on('change', function() {
                aspectRatioBlock.removeClass('has-error');
                aspectRatioError.text('');      
            });
        });
        $(".preview").click(function() {
            $("#is-preview-input").val(1);
            if (!AutoBrest.photoValidate()) {
                return;
            }
            $("#add-tire").submit();
        });
        
        var modelSelector = $("#addtireform-model_id");
        function loadModels(id) {
            $.ajax({
                url: '/main/tires/get-models/',
                dataType: "json",
				type: "POST",
				data: {id: id, _csrf: yii.getCsrfToken()},
				beforeSend: function () {
					modelSelector.hide().parent().append('<img src="/images/ajax-loader2.gif">');
				},
				success: function(data, textStatus, jqXHR) {
					if (data.result == 'success') {
					    modelSelector.find("option:not(:first)").remove();
						$.each(data.items, function(id, item) {
						    modelSelector.append('<option value="' + item.id + '">' + item.name + '</option>');
						});
					}
				},
				complete: function () {
					modelSelector.show().parent().find("img").remove();
				}
            });
        }
        
        //photoValidate();
    })(jQuery);
JS;
$this->registerJs($js);

?>
<div class="content-left">
    <?= $this->render('../common/left-menu') ?>
</div>
<div class="content-right">
     <div class="breadcrumbs">
       <a href="/">Главная</a> > <a href="javascript:void(0);">Добавить шины</a>
    </div>

    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => Yii::$app->user->identity]) ?>

    <?php
    $addTireForm = ActiveForm::begin([
        //'action' => ['/main/cars/add'],
        'id' => 'add-tire',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);

        echo $addTireForm->field($formModel, 'isPreview')->hiddenInput(['id' => 'is-preview-input'])->label(false); ?>
        <div class="form-add-mobile-title">
            <h1>Добавить шины</h1>
        </div>
        <div class="form-add">
            <div class="add-block">
                <div class="wrap">
                    <p class="form-add-title">Добавить шины</p>
                    <div class="middle-btn-group">
                        <div class="btn-group-title">Тип шин</div>
                        <?= FormHelper::radioList($addTireForm, $formModel, 'tire_type', AutoHelper::getTireTypesArray()) ?>
                    </div>

                    <div class="middle-btn-group middle-btn-group-nomargin">
                        <div class="btn-group-title">Состояние шин</div>
                        <?= $addTireForm->field($formModel, 'is_new')->radioList([1 => 'Новые', 0 => 'С пробегом'], [
                            'item' => function($index, $label, $name, $checked, $value) {
                                $return = '<label class="block-radio ib' . ($checked ? ' active' : '') . '">';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                $return .= '<span>' . $label . '</span>';
                                $return .= '</label>';
                                return $return;
                            }
                        ])->label(false); ?>
                    </div>

                    <div class="middle-btn-group">
                        <div class="btn-group-title">Производитель шин</div>
                        <?= $addTireForm->field($formModel, 'brand_id', [
                            'template' => '<div class="select-large-radius">{input}</div>{error}'
                        ])->dropDownList(TireBrand::itemsArray(), ['id' => 'addtireform-brand_id', 'prompt' => 'Выбрать']) ?>
                    </div>
                    <div class="middle-btn-group">
                        <div class="btn-group-title">Модель шин</div>
                        <?= $addTireForm->field($formModel, 'model_id', [
                            'template' => '<div class="select-large-radius">{input}</div>{error}'
                        ])->dropDownList(TireModel::getModelsByBrand($formModel->brand_id), ['id' => 'addtireform-model_id', 'prompt' => 'Выбрать']) ?>
                    </div>

                    <div class="middle-btn-group linked-group">
                        <div class="btn-group-title">Сезонность шин</div>
                        <?= FormHelper::radioList($addTireForm, $formModel, 'season', AutoHelper::getTireSeasonsArray()) ?>
                    </div>

                     <div class="middle-btn-group linked-group">
                        <div class="btn-group-title">Параметры</div>
                        <div class="linked-group-flex">
                            <div class="linked-group-block">
                                <?= $addTireForm->field($formModel, 'radius', [
                                    'template' => '<div class="select-middle-radius">{input}</div>{error}',
                                    'errorOptions' => ['tag' => 'span'],
                                    'options' => ['tag' => false]
                                ])->dropDownList(AutoHelper::getTireRadiusArray(), ['prompt' => 'Радиус']) ?>
                            </div>
                            <div class="linked-group-block">
                                <?= $addTireForm->field($formModel, 'width', [
                                    'template' => '<div class="select-middle-radius">{input}</div>{error}',
                                    'errorOptions' => ['tag' => 'span'],
                                    'options' => ['tag' => false]
                                ])->dropDownList(AutoHelper::getTireWidthArray(), ['prompt' => 'Ширина']) ?>
                            </div>
                            <div class="linked-group-block">
                                <?= $addTireForm->field($formModel, 'aspect_ratio', [
                                    'template' => '<div class="select-middle-radius">{input}</div>{error}',
                                    'errorOptions' => ['tag' => 'span'],
                                    'options' => ['tag' => false]
                                ])->dropDownList(AutoHelper::getTireAspectRatioArray(), ['prompt' => 'Высота']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="middle-btn-group">
                        <div class="btn-group-title">Количество шин</div>
                        <?= $addTireForm->field($formModel, 'amount')->radioList(AutoHelper::getTireAmountArray(), [
                            'item' => function($index, $label, $name, $checked, $value) {
                                $return = '<label class="block-radio ib' . ($checked ? ' active' : '') . '">';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                $return .= '<span>' . $label . '</span>';
                                $return .= '</label>';
                                return $return;
                            }
                        ])->label(false); ?>
                    </div>
                </div>
            </div>
            <div class="add-block add-photos-block">
                <div class="wrap">
                    <div class="add-photos">
                        <div class="btn-group-title">Добавьте фотографии (минимум 1 шт.)</div>
                        <?php PhotoHelper::renderPhotosForm(Ad::TYPE_TIRE, $formModel->saved_photos, $formModel); ?>
                    </div>
                </div>
            </div>
            <div class="add-block add-descr-block">
                <div class="wrap">
                    <div class="add-block-row">
                        <?= $addTireForm->field($formModel, 'price', [
                            'template' => '<div class="input-large-radius ib">{input}</div> <span class="add-block-price">бел.руб</span>{error}'
                        ])->input("number", ['placeholder' => 'Укажите цену', 'id' => 'addtireform-price']) ?>
                    </div>

                    <?php /*
                    <div class="add-block-row">
                        <?= $addTireForm->field($formModel, 'priceUSD', [
                            'template' => '<div class="input-large-radius ib">{input}</div> <span class="add-block-price">дол.США</span>{error}'
                        ])->input("number", ['placeholder' => 'Укажите цену', 'id' => 'addtireform-price-usd']) ?>
                    </div>
                    */ ?>

                    <div class="add-block-row middle-btn-group linked-group linked-group-couple">
                        <div class="btn-group-title">Торг возможен</div>
                        <?= $addTireForm->field($formModel, 'bargain')->radioList([1 => "Да", 0 => "Нет"], [
                            'item' => function($index, $label, $name, $checked, $value) {
                                $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                $return .= '<span>' . $label . '</span>';
                                $return .= '</div>';
                                return $return;
                            }
                        ])->label(false) ?>
                    </div>
                    
                    <div class="add-block-row">
                        <div class="btn-group-title">Введите описание</div>
                        <?= $addTireForm->field($formModel, 'description', [
                            'template' => '<div class="field-description">{input}{error}</div>'
                        ])->textarea(['placeholder' => 'Минимум 15 символов...']) ?>
                    </div>

                    <div class="add-block-row">
                        <div class="btn-group-title">Дайте оценку по 6-ти бальной шкале, где:</div>
                        <?= $addTireForm->field($formModel, 'condition')->radioList(AutoHelper::getTireConditionArray(), [
                            'item' => function($index, $label, $name, $checked, $value) {
                                $return = '<label class="block-radio">';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                $return .= ' ' . $label;
                                $return .= '</label>';
                                return $return;
                            }
                        ])->label(false) ?>
                    </div>
                </div>
            </div>


            <?php /*= $this->render("../common/user-fields", [
                'form' => $addTireForm,
                'model' => $formModel
            ]) */ ?>

            <div class="add-block">
                <div class="wrap">
                    <!-- DELETE THAT CODE <?php  if (!$formModel->id || $formModel->status == Ad::STATUS_PREVIEW) : ?>
                    <div class="preview">Предварительный просмотр</div>
                    <?php endif; ?>-->
                    <div class="publish-add"><?= ($formModel->status == Ad::STATUS_ACTIVE) ? "Сохранить" : "Опубликовать" ?> объявление</div>
                </div>
            </div>
        </div>
    <?php  ActiveForm::end(); ?>
</div>
