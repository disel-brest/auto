<?php

use app\components\Currency;
use app\helpers\AutoHelper;
use app\modules\main\widgets\CarOptionsWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $filter \app\modules\main\models\filters\CarsFilter */

$this->title = "Автомобили - Полный фильтр поиска";

?>
<div class="content-left">
    <?= $this->render('../common/filter-left-content') ?>
</div>
<div class="content-right">
    <div class="filter-content">
        <?php $form = ActiveForm::begin([
            'action' => ['/main/cars/index'],
            'id' => 'full-filter-auto',
            'method' => 'get',
        ]) ?>
            <?= $form->field($filter, 'brand')->hiddenInput()->label(false) ?>
            <?= $form->field($filter, 'model')->hiddenInput()->label(false) ?>

            <div class="full-filter-block">
                <h3>Полный фильтр по легковым авто</h3>
                <div class="select-mark select-large-poluradius">
                    <a href="#select-mark-pop-up">
                        <span><?= $filter->brand ? AutoHelper::getBrandNameById($filter->brand) : "Выберите марку" ?></span>
                    </a>
                </div>
                <div class="select-model select-large-poluradius">
                    <a href="#select-model-pop-up">
                        <span><?= $filter->model ? AutoHelper::getModelNameById($filter->model) : "Выберите модель" ?></span>
                    </a>
                </div>
                <div class="input-large-poluradius">
                    <?= $form->field($filter, 'year_min', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'Год от', 'class' => 'select-small']) ?>
                    <?= $form->field($filter, 'year_max', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'До', 'class' => 'select-small']) ?>
                </div>
                <div class="input-large-poluradius">
                    <?= $form->field($filter, 'odometer_min', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::getOdometersArray(), ['prompt' => 'Пробег от', 'class' => 'select-small']) ?>
                    <?= $form->field($filter, 'odometer_max', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::getOdometersArray(), ['prompt' => 'До', 'class' => 'select-small']) ?>
                </div>

                <?= $form->field($filter, 'bodyStyle', [
                    'template' => '<div class="select-large-poluradius">{input}</div>',
                ])->dropDownList(AutoHelper::BODY_STYLES, ['prompt' => 'Тип кузова']) ?>

                <?= $form->field($filter, 'fuel', [
                    'template' => '<div class="select-type-engine-transport"><p>Тип двигателя</p>{input}</div>',
                ])->checkboxList(AutoHelper::FUEL_TYPES, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<label>';
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $label;
                        $return .= '<span class="count"> 35</span>';
                        $return .= '</label>';
                        return $return;
                    }
                ]) ?>

                <div class="input-large-poluradius">
                    <?= $form->field($filter, 'engineVolume_min', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'Объем от', 'class' => 'select-small']) ?>
                    <?= $form->field($filter, 'engineVolume_max', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'До', 'class' => 'select-small']) ?>
                </div>

                <?= $form->field($filter, 'transmission', [
                    'template' => '<div class="select-type-gear-transport"><p>Выберите тип КПП</p>{input}</div>',
                ])->checkboxList(AutoHelper::TRANSMISSION_TYPES, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<label>';
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $label;
                        $return .= '<span class="count"> 35</span>';
                        $return .= '</label>';
                        return $return;
                    }
                ]) ?>

                <?= $form->field($filter, 'drivetrain', [
                    'template' => '<div class="select-type-drive-transport"><p>Тип привода</p>{input}</div>',
                ])->checkboxList(AutoHelper::DRIVETRAIN_TYPES, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<label>';
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $label;
                        $return .= '<span class="count"> 35</span>';
                        $return .= '</label>';
                        return $return;
                    }
                ]) ?>

                <?= $form->field($filter, 'color', [
                    'template' => '<div class="select-large-poluradius">{input}</div>',
                ])->dropDownList(AutoHelper::COLORS_ARRAY, ['prompt' => 'Выберите цвет']) ?>

            </div>

            <div class="title-row">Выберите опции</div>
            <div class="select-dop-option-transport-block">
                <?= CarOptionsWidget::widget(['form' => $filter]) ?>
            </div>

            <div class="select-dop-option-transport-block">
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

                <div class="small-btn-group">
                    <span>Обмен</span>
                    <?= $form->field($filter, 'change')->radioList([1 => "Да", 0 => "Нет"], [
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
                    <span>Безнал</span>
                    <?= $form->field($filter, 'lawFirm')->radioList([1 => "Да", 0 => "Нет"], [
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
            <div class="title-row last">
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
            <a href="<?= Url::to(['/main/cars/index']) ?>">X</a>
        </div>
    </div>
</div>