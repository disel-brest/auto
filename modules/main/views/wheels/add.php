<?php

use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\helpers\FormHelper;
use app\modules\main\models\Ad;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $formModel \app\modules\main\forms\AddWheelForm */

$ue_course = Yii::$app->currency->getExchangeValue();
$js = <<<JS
    (function() {
        var ue_course = {$ue_course};
        $("#addwheelform-price").on("change keyup", function() {
            var in_ue = Math.round(($(this).val() * ue_course) * 100) / 100;
            $("#price_in_ue").text(in_ue);
        });
        
        $('#add-wheel').on('click', '.publish-add', function () {
            $("#is-preview-input").val(0);
            if (!AutoBrest.photoValidate()) {
                return;
            }
            $("#add-wheel").submit();
            
            var radius = $('#addwheelform-radius');
            var radiusBlock = radius.closest('.linked-group-block');
            var radiusError = radius.parent().parent().find('.help-block-error');

            var bolts = $('#addwheelform-bolts');
            var boltsBlock = bolts.closest('.linked-group-block');
            var boltsError = bolts.parent().parent().find('.help-block-error');

            if (!radius.val()) {
                radiusBlock.addClass('has-error');
                radiusError.text('Укажите радиус');
            }
            if (!bolts.val()) {
                boltsBlock.addClass('has-error');
                boltsError.text('Укажите кол-во болтов');
            }

            radius.on('change', function() {
                radiusBlock.removeClass('has-error');
                radiusError.text('');    
            });
            bolts.on('change', function() {
                boltsBlock.removeClass('has-error');
                boltsError.text('');    
            });
        });
        $(".preview").click(function() {
            $("#is-preview-input").val(1);
            if (!AutoBrest.photoValidate()) {
                return;
            }
            $("#add-wheel").submit();
        });
        $(".select_mark_pop-up_content .all_marks").click(function() {
            var form = $("#add-wheel");
            form.find("#addwheelform-auto-brand-id").val('');
            form.find(".middle-btn-group.select-mark a > span").html("Все марки");
            $.magnificPopup.close();
        });
        
    })(jQuery);
JS;
$this->registerJs($js);

?>
<div class="content-left">
    <?= $this->render('../common/left-menu') ?>
</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="/">Главная</a> > <a href="javascript:void(0);">Добавить диски</a>
    </div>

    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => Yii::$app->user->identity]) ?>

    <?php
    $addWheelForm = ActiveForm::begin([
        'id' => 'add-wheel',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);

        echo $addWheelForm->field($formModel, 'isPreview')->hiddenInput(['id' => 'is-preview-input'])->label(false);
        echo $addWheelForm->field($formModel, 'auto_brand_id')->hiddenInput(['id' => 'addwheelform-auto-brand-id'])->label(false);
    ?>
    <div class="form-add-mobile-title">
        <h1>Добавить диски</h1>
    </div>
    <div class="form-add">
        <div class="add-block">
            <div class="wrap">
                <p class="form-add-title">Добавить диски</p>
                <div class="middle-btn-group type-body">
                    <div class="btn-group-title">Тип дисков</div>
                    <?= FormHelper::radioList($addWheelForm, $formModel, 'auto_type', AutoHelper::getWheelAutoArray(true)) ?>
                </div>
                <div class="middle-btn-group middle-btn-group-nomargin">
                    <div class="btn-group-title">Состояние дисков</div>
                    <?= $addWheelForm->field($formModel, 'is_new', [
                        'template' => '<div class="add-block-row">{input}</div>{error}',
                    ])->radioList([1 => 'Новые', 0 => 'С пробегом'], [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $return = '<label class="block-radio ib">';
                            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                            $return .= '<span> ' . $label . '</span>';
                            $return .= '</label>';
                            return $return;
                        }
                    ]) ?>
                </div>
                <div class="middle-btn-group">
                    <div class="btn-group-title">Тип дисков</div>
                    <?= $addWheelForm->field($formModel, 'wheel_type', [
                        'template' => '<div class="select-large-radius">{input}</div>{error}'
                    ])->dropDownList(AutoHelper::getWheelTypesArray(), ['prompt' => 'Выбрать']) ?>
                </div>
                <div class="middle-btn-group select-mark">
                    <div class="btn-group-title">Для марки авто</div>                
                    <div class="select-mark select-large-radius">
                        <a href="#select-mark-pop-up">
                            <span><?= $formModel->auto_brand_id ? AutoHelper::getBrandNameById((int)$formModel->auto_brand_id) : "Выбрать" ?></span>
                        </a>
                    </div>
                </div>
                <div class="add-block-row">
                    <div class="btn-group-title">Производитель дисков</div>                
                    <?= $addWheelForm->field($formModel, 'firm', ['options' => ['class' => 'input-large-radius']])
                        ->label(false)
                        ->textInput(['placeholder' => 'Введите название'])?>
                </div>
                <div class="middle-btn-group linked-group linked-group-couple">
                    <div class="btn-group-title">Параметры</div>
                    <div class="linked-group-flex">
                        <div class="linked-group-block">    
                            <?= $addWheelForm->field($formModel, 'radius', [
                                'template' => '<div class="select-middle-radius wheel-radius">{input}</div>{error}',
                                'errorOptions' => ['tag' => 'span'],
                                'options' => ['tag' => false]
                            ])->dropDownList(AutoHelper::getTireRadiusArray(), ['prompt' => 'Радиус']) ?>
                        </div>
                        <div class="linked-group-block">
                            <?= $addWheelForm->field($formModel, 'bolts', [
                                'template' => '<div class="select-middle-radius count-boltes">{input}</div>{error}',
                                'errorOptions' => ['tag' => 'span'],
                                'options' => ['tag' => false]
                            ])->dropDownList(AutoHelper::getWheelBoltsArray(), ['prompt' => 'Кол-во болтов']) ?>
                        </div>    
                    </div>
                </div>
                <div class="middle-btn-group">
                    <div class="btn-group-title">Количество дисков</div>                
                    <?= $addWheelForm->field($formModel, 'amount', [
                        'template' => /*<div class="add-block-row">*/'{input}{error}',
                    ])->radioList(AutoHelper::getTireAmountArray(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $return = '<label class="block-radio ib">';
                            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                            $return .= '<span> ' . $label . '</span>';
                            $return .= '</label>';
                            return $return;
                        }
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="add-block add-photos-block">
            <div class="wrap">
                <div class="add-photos">
                    <div class="btn-group-title">Добавьте фотографии (минимум 1шт.)</div>                
                    <?php PhotoHelper::renderPhotosForm(Ad::TYPE_TIRE, $formModel->saved_photos, $formModel); ?>
                </div>
            </div>
        </div>
        <div class="add-block add-descr-block">
            <div class="wrap">
                <div class="add-block-row">
                    <?= $addWheelForm->field($formModel, 'price', [
                        'template' => '<div class="input-large-radius ib">{input}</div> <span class="add-block-price">бел.руб</span>{error}'
                    ])->input("number", ['placeholder' => 'Укажите цену', 'id' => 'addwheelform-price']) ?>
                </div>

                <?php /*<div class="add-block-row">
                    <?= $addWheelForm->field($formModel, 'priceUSD', [
                        'template' => '<div class="input-large-radius ib">{input}</div> <span class="add-block-price">дол.США</span>{error}'
                    ])->input("number", ['placeholder' => 'Укажите цену', 'id' => 'addwheelform-priceusd']) ?>
                </div> */ ?>

                <div class="add-block-row middle-btn-group linked-group linked-group-couple">
                    <div class="btn-group-title">Торг возможен</div>
                    <?= $addWheelForm->field($formModel, 'bargain')->radioList([1 => "Да", 0 => "Нет"], [
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
                    <?= $addWheelForm->field($formModel, 'description', [
                        'template' => '<div class="field-description">{input}{error}</div>'
                    ])->textarea(['placeholder' => 'Минимум 15 символов ...']) ?>
                </div>

                <div class="add-block-row">
                    <div class="btn-group-title">Дайте оценку по 6-ти бальной шкале, где:</div>
                    <?= $addWheelForm->field($formModel, 'condition')->radioList(AutoHelper::getTireConditionArray(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $return = '<label class="block-radio">';
                            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                            $return .= '<span> ' . $label . '</span>';
                            $return .= '</label>';
                            return $return;
                        }
                    ])->label(false) ?>
                </div>
            </div>
        </div>

        <?php /*= $this->render("../common/user-fields", [
            'form' => $addWheelForm,
            'model' => $formModel
        ])*/ ?>

        <div class="add-block">
            <div class="wrap">
                <!-- DELETE THAT CODE<?php  if (!$formModel->id || $formModel->status == Ad::STATUS_PREVIEW) : ?>
                    <div class="preview">Предварительный просмотр</div>
                <?php endif; ?>-->
                <div class="publish-add"><?= ($formModel->status == Ad::STATUS_ACTIVE) ? "Сохранить" : "Опубликовать" ?> объявление</div>
            </div>
        </div>
    </div>
    <?php  ActiveForm::end(); ?>
</div>
