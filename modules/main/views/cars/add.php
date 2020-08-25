<?php

use app\helpers\AutoHelper;
use app\modules\main\models\Ad;
use app\modules\main\widgets\CarOptionsWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $formModel \app\modules\main\forms\AddCarForm */

$ue_course = Yii::$app->currency->getExchangeValue();
$js = <<<JS
    (function() {
        var ue_course = {$ue_course};
        $("#addcarform-price").on("change keyup", function() {
            var in_ue = Math.round(($(this).val() * ue_course) * 100) / 100;
            $("#price_in_ue").text(in_ue);
        });
        $('#add-auto').on('click', '.publish-add', function () {
            $("#is-preview-input").val(0);
            $("#add-auto").submit();
        });
        $(".preview").click(function() {
            $("#is-preview-input").val(1);
            $("#add-auto").submit();
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
        <a href="<?= Yii::$app->homeUrl ?>">Главная</a> > <a href="<?= Url::to(['/user/cabinet/index']) ?>">Личный кабинет</a>
    </div>

    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => Yii::$app->user->identity]) ?>

    <?php
    $addCarForm = ActiveForm::begin([
        //'action' => ['/main/cars/add'],
        'id' => 'add-auto',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);

    echo $addCarForm->field($formModel, 'isPreview')->hiddenInput(['id' => 'is-preview-input'])->label(false);

    echo $addCarForm->field($formModel, 'brand_id')->hiddenInput()->label(false);
    echo $addCarForm->field($formModel, 'model_id')->hiddenInput()->label(false);
    ?>
        <div class="add-block">
            <div class="select-mark select-large-poluradius">
                <a href="#select-mark-pop-up">
                    <span><?= $formModel->brand_id ? AutoHelper::getBrandNameById((int)$formModel->brand_id) : "Выберите марку" ?></span>
                </a>
            </div>
            <div class="select-model select-large-poluradius">
                <a href="#select-model-pop-up">
                    <span><?= $formModel->model_id ? AutoHelper::getModelNameById((int)$formModel->model_id) : "Выберите модель" ?></span>
                </a>
            </div>

            <?= $addCarForm->field($formModel, 'year', [
                'template' => '<div class="select-large-poluradius">{input}</div>{error}'
            ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'Выберите год выпуска']) ?>

            <?= $addCarForm->field($formModel, 'odometer', [
                'template' => '<div class="input-large-poluradius">{input}</div>{error}'
            ])->input("number", ['placeholder' => 'Введите пробег,км']) ?>

            <?= $addCarForm->field($formModel, 'body_style', [
                'template' => '<div class="select-large-poluradius">{input}</div>{error}'
            ])->dropDownList(AutoHelper::BODY_STYLES, ['prompt' => 'Тип кузова']) ?>

            <div class="middle-btn-group">
                <?= $addCarForm->field($formModel, 'fuel_id')->radioList(AutoHelper::FUEL_TYPES, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" class="adpart-fuel"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span>' . $label . '</span>';
                        $return .= '</div>';
                        return $return;
                    }
                ])->label(false) ?>
            </div>

            <?= $addCarForm->field($formModel, 'engine_volume', [
                'template' => '<div class="select-large-poluradius">{input}</div>{error}'
            ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'Выберите объем двигателя']) ?>

            <div class="middle-btn-group">
                <?= $addCarForm->field($formModel, 'transmission')->radioList(AutoHelper::TRANSMISSION_TYPES, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" class="adpart-fuel"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span>' . $label . '</span>';
                        $return .= '</div>';
                        return $return;
                    }
                ])->label(false) ?>
            </div>

            <div class="middle-btn-group">
                <?= $addCarForm->field($formModel, 'drivetrain')->radioList(AutoHelper::DRIVETRAIN_TYPES, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" class="adpart-fuel"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span>' . $label . '</span>';
                        $return .= '</div>';
                        return $return;
                    }
                ])->label(false) ?>
            </div>

            <?= $addCarForm->field($formModel, 'color', [
                'template' => '<div class="select-large-poluradius">{input}</div>{error}'
            ])->dropDownList(AutoHelper::COLORS_ARRAY, ['prompt' => 'Выберите цвет']) ?>
        </div>
        <div class="title-row">
            Выберите опции
        </div>
        <div class="add-block">
            <?= CarOptionsWidget::widget(['form' => $formModel]) ?>
        </div>
        <div class="add-photos-block">
            <p>Прикрепите фото</p>
            <div class="add-photos">
                <?php
                for ($i = 1; $i < 6; $i++) {
                    ?>
                    <div class="add-photo">
                        <div class="add-photo-img">
                            <?php if (isset($formModel->saved_photos[$i-1])) : ?>
                                <img src="<?= $formModel->saved_photos[$i-1] ?>">
                            <?php else : ?>
                                <p>Прикрепить фото <?= $i ?></p>
                            <?php endif; ?>
                        </div>
                        <?= $addCarForm->field($formModel, 'photo[]')->fileInput(['class' => 'car-photo-file-input'])->label(false) ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="add-block">
            <?= $addCarForm->field($formModel, 'price', [
                'template' => '<div class="input-large-poluradius ib">{input}</div> <span>бел.руб</span><span> = <em id="price_in_ue">0</em> y.e</span>{error}'
            ])->input("number", ['placeholder' => 'Укажите цену', 'id' => 'addcarform-price']) ?>

            <div class="small-btn-group">
                <span>Торг</span>
                <?= $addCarForm->field($formModel, 'bargain')->radioList([0 => "Нет", 1 => "Да"], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<div class="small-btn' . ($checked ? ' active' : '') . '">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span>' . $label . '</span>';
                        $return .= '</div>';
                        return $return;
                    }
                ])->label(false) ?>
            </div>
            <div class="small-btn-group">
                <span>Обмен</span>
                <?= $addCarForm->field($formModel, 'change')->radioList([0 => "Нет", 1 => "Да"], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<div class="small-btn' . ($checked ? ' active' : '') . '">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span>' . $label . '</span>';
                        $return .= '</div>';
                        return $return;
                    }
                ])->label(false) ?>
            </div>

            <?= $addCarForm->field($formModel, 'law_firm', [
                'template' => '<div><label>{input}</label>{error}</div>'
            ])->checkbox() ?>

            <?= $addCarForm->field($formModel, 'description', [
                'template' => '<div class="field-description">{input}{error}</div>'
            ])->textarea(['placeholder' => 'Описание...']) ?>
        </div>

        <?= $this->render("../common/user-fields", [
            'form' => $addCarForm,
            'model' => $formModel,
        ]) ?>

        <div class="add-block">
            <?php  if (!$formModel->id || $formModel->status == Ad::STATUS_PREVIEW) : ?>
                <div class="preview">Предварительный просмотр</div>
            <?php endif; ?>
            <div class="publish-add"><?= $formModel->id ? "Сохранить" : "Опубликовать" ?> объявление</div>
        </div>
    <?php  ActiveForm::end(); ?>
</div>
