<?php

use app\helpers\AutoHelper;
use app\modules\main\widgets\PartsCategoryWidget;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;

/* @var $this \yii\web\View */
/* @var $formModel \app\modules\main\forms\AddPartForm */

?>
<div class="content-left">
    <?= $this->render('../common/left-menu') ?>
</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="#">Главная</a> > <a href="javascript:void(0);">Добавить автозапчасти б/у</a>
    </div>

    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => Yii::$app->user->identity]) ?>

    <?php
    $addPartForm = ActiveForm::begin([
        'action' => ['/main/parts/add'],
        'id' => 'add-part-form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
        <div class="form-add-mobile-title">
            <h1>Добавить автозапчасти</h1>
        </div>
        <div class="add-part-form">
            <div class="form-add-title">Добавить автозапчасти б/у</div>
            <div class="form-add">
                <div class="title-block">
                    <p>1-Выберите для какой марки будем добавлять запчасти</p>
                </div>
                <div class="add-block">
                    <div class="wrap">
                        <div class="middle-btn-group select-mark">
                            <div class="btn-group-title">Марка авто</div>
                            <div class="select-mark select-large-radius">
                                <a href="#select-mark-pop-up">
                                    <span><?= $formModel->brand_id ? AutoHelper::getBrandNameById((int)$formModel->brand_id) : "Выбрать" ?></span>
                                </a>
                            </div>
                            <?php echo $addPartForm->field($formModel, 'brand_id')->hiddenInput()->label(false); ?>
                        </div>
                        <div class="middle-btn-group select-model">
                            <div class="btn-group-title">Модель авто</div>
                            <div class="select-model select-large-radius">
                                <a href="#select-model-pop-up">
                                    <span><?= $formModel->model_id ? AutoHelper::getModelNameById((int)$formModel->model_id) : "Выбрать" ?></span>
                                </a>
                            </div>
                            <?php echo $addPartForm->field($formModel, 'model_id')->hiddenInput()->label(false); ?>
                        </div>

                        <div class="middle-btn-group type-engine middle-btn-group-radio">
                            <div class="btn-group-title">Тип двигателя</div>
                            <?= $addPartForm->field($formModel, 'fuel_id')->radioList(AutoHelper::FUEL_TYPES, [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" class="adpart-fuel"' . ($checked ? ' checked' : '') . '>';
                                    $return .= '<span>' . $label . '</span>';
                                    $return .= '</div>';
                                    return $return;
                                }
                            ])->label(false) ?>
                        </div>

                        <div class="middle-btn-group middle-btn-group-mobile">
                            <div class="btn-group-title">Тип двигателя</div>
                            <div class="select-large-radius">
                                <select>
                                    <option>Выбрать</option>
                                </select>
                            </div>
                        </div>

                        <div class="middle-btn-group engine-volume">
                            <div class="btn-group-title">Объем двигателя</div>
                            <?= $addPartForm->field($formModel, 'engine_volume', [
                                'template' => '<div class="select-large-radius">{input}</div>{error}'
                            ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'Выбрать']) ?>                          
                        </div>
                        <div class="middle-btn-group select-year">
                            <div class="btn-group-title">Год выпуска</div>
                            <?= $addPartForm->field($formModel, 'year', [
                                'template' => '<div class="select-large-radius">{input}</div>{error}'
                            ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'Выбрать']) ?>
                        </div>

                        <div class="middle-btn-group type-body middle-btn-group-radio">
                            <div class="btn-group-title">Тип кузова</div>
                            <?= $addPartForm->field($formModel, 'body_style')->radioList(AutoHelper::BODY_STYLES, [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                    $return .= '<span>' . $label . '</span>';
                                    $return .= '</div>';
                                    return $return;
                                }
                            ])->label(false) ?>
                        </div>

                        <div class="middle-btn-group type-body middle-btn-group-mobile">
                            <div class="btn-group-title">Тип кузова</div>
                            <div class="select-large-radius">
                                <select>
                                    <option>Выбрать</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-add">
                <div class="title-block">
                    <p>2-Отметьте в нужных категориях запчасти на продажу [ поставьте галочки ]</p>
                </div>
                
                <div class="add-block">
                    <div class="add-part-category-wrap">
                        <div class="add-part-category-list">
                            <?= PartsCategoryWidget::widget() ?>
                        </div>
                    </div>
                </div>

                <?php /*= $this->render("../common/user-fields", [
                    'form' => $addPartForm,
                    'model' => $formModel,
                ])*/ ?>

                <div class="add-block">
                    <div class="wrap">
                        <div class="parts-empty-error help-block help-block-error"></div>                       
                            <!--<div class="preview">Предварительный просмотр</div>-->
                        <div class="publish-add">Опубликовать объявление</div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>