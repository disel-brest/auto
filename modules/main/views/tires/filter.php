<?php

use app\components\Currency;
use app\helpers\AutoHelper;
use app\helpers\FormHelper;
use app\modules\main\models\TireBrand;
use app\modules\main\models\TireModel;
use app\modules\main\widgets\CarOptionsWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $filter \app\modules\main\models\filters\TiresFilter */

$this->title = "Шины - Полный фильтр поиска";
$js = <<<JS
    $(document).on("change", "#tiresfilter-brand", function() {
        AutoBrest.loadTireModels($(this), $("#tiresfilter-model"));
    });
JS;
$this->registerJs($js);

?>
<div class="content-left">
    <?= $this->render('../common/filter-left-content') ?>
</div>
<div class="content-right">
    <div class="filter-content">
        <?php $form = ActiveForm::begin([
            'action' => ['/main/tires/index'],
            'id' => 'full-filter-tire',
            'method' => 'get',
        ]) ?>

            <div class="full-filter-block">
                <h3>Полный фильтр по шинам</h3>

                <div class="middle-btn-group">
                    <?= FormHelper::radioList($form, $filter, 'tire_type', AutoHelper::getTireTypesArray()) ?>
                </div>

                <p>Выберите состояние шин</p>
                <?= $form->field($filter, 'is_new', [
                    'template' => '<div class="add-block-row">{input}</div>',
                ])->checkboxList([1 => 'новые', 0 => 'с пробегом'], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<p>';
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span> ' . $label . '<span class="count">16</span></span>';
                        $return .= '</p>';
                        return $return;
                    }
                ]) ?>

                <?= $form->field($filter, 'brand', [
                    'template' => '<div class="select-large-poluradius">{input}</div>'
                ])->dropDownList(TireBrand::itemsArray(), ['prompt' => 'Выберите производителя', 'id' => 'tiresfilter-brand']) ?>

                <?= $form->field($filter, 'model', [
                    'template' => '<div class="select-large-poluradius">{input}</div>'
                ])->dropDownList(TireModel::getModelsByBrand($filter->brand), ['prompt' => 'Выберите модель', 'id' => 'tiresfilter-model']) ?>

                <p>Выберите тип шин</p>
                <?= $form->field($filter, 'season', [
                    'template' => '<div class="add-block-row">{input}</div>',
                    'options' => ['tag' => false],
                ])->checkboxList(AutoHelper::getTireSeasonsArray(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<p>';
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span> ' . $label . '<span class="count">16</span></span>';
                        $return .= '</p>';
                        return $return;
                    }
                ]) ?>

                <?= $form->field($filter, 'radius', [
                    'template' => '<div class="select-middle-poluradius ib">{input}</div>',
                    'options' => ['tag' => false],
                ])->dropDownList(AutoHelper::getTireRadiusArray(), ['prompt' => 'Радиус']) ?>

                <?= $form->field($filter, 'width', [
                    'template' => '<div class="select-middle-poluradius ib">{input}</div>',
                    'options' => ['tag' => false],
                ])->dropDownList(AutoHelper::getTireWidthArray(), ['prompt' => 'Ширина']) ?>

                <?= $form->field($filter, 'aspect_ratio', [
                    'template' => '<div class="select-middle-poluradius ib">{input}</div>',
                    'options' => ['tag' => false],
                ])->dropDownList(AutoHelper::getTireAspectRatioArray(), ['prompt' => 'Высота']) ?>

                <p>Выберите кол-во шин</p>
                <?= $form->field($filter, 'amount', [
                    'template' => '<div class="add-block-row">{input}</div>',
                    'options' => ['tag' => false],
                ])->checkboxList(AutoHelper::getTireAmountArray(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<p>';
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                        $return .= '<span> ' . $label . '<span class="count">16</span></span>';
                        $return .= '</p>';
                        return $return;
                    }
                ]) ?>

                <div class="input-large-poluradius ib">
                    <?= $form->field($filter, 'price_min', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::getPriceArray(), ['prompt' => 'Цена от', 'class' => 'select-small']) ?>
                    <?= $form->field($filter, 'price_max', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::getPriceArray(), ['prompt' => 'До', 'class' => 'select-small']) ?>
                </div>

                <?= $form->field($filter, 'currency', [
                    'options' => ['tag' => false],
                    'template' => '<div class="select-small-poluradius ib type-currency">{input}</div>'
                ])->dropDownList(Currency::getCurrenciesArray(), ['class' => '']) ?>

                <div class="small-btn-group">
                    <span>Торг</span>
                    <?= $form->field($filter, 'bargain')->radioList([1 => "Да", 0 => "Нет"], [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $return = '<div class="small-btn' . ($checked ? ' active' : '') . '">';
                            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                            $return .= '<span>' . $label . '</span>';
                            $return .= '</div>';
                            return $return;
                        }
                    ])->label(false) ?>
                </div>

            </div>
            <div class="title-row">
                <div class="select-city-for-transport">
                    <!--<span>Брестская область</span>-->
                    <?= $form->field($filter, 'city', ['options' => ['class' => 'input-large-poluradius ib']])
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
                        ]) ?>

                </div>
            </div>
            <div class="full-filter-btn-block">
                <input type="submit" value="Найти">
            </div>
        <?php ActiveForm::end(); ?>
        <div class="close-filter-content">
            <a href="<?= Url::to(['/main/tires/index']) ?>">X</a>
        </div>
    </div>
</div>