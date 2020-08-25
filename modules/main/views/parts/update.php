<?php

use app\helpers\AutoHelper;
use app\modules\main\widgets\PartsCategoryWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \app\modules\main\forms\PartForm */
/* @var $adPart \app\modules\main\models\AdPart */

$js = <<<JS
    (function() {
        window.addEventListener("load", function() {
            $(".photo-block-content").on('click', '.add-part-string', function(e) {
                if (!$(e.target).hasClass('photo-btn-delete')) {
                    $(this).find(".photo-input")[0].click();
                } else {
                    var block = $(this);
                    block.find('.photo-input').val('');
                    block.find('.photo-btn-delete').removeClass('shown');
                    block.find('img').remove().end().prepend('<p>Добавить фото</p>');
                    block.find('input[name*=toRemove]').val('1');
                }
            });
            
            $('.photo-input').on("change", function (evt) {
                var tgt = evt.target || window.event.srcElement,
                    files = tgt.files;
                var imgEl = $(this).parent().parent().parent();
                console.log(imgEl);
                console.log($(this));
                // FileReader support
                if (FileReader && files && files.length) {
                    var fr = new FileReader();
                    fr.onload = function () {
                        imgEl.find('img').remove();
                        imgEl.find('p').remove();
                        imgEl.find('.photo-btn-delete').addClass('shown');
                        imgEl.prepend('<img src="' + fr.result + '">');
                    };
                    fr.readAsDataURL(files[0]);
                }
        
                // Not supported
                else {
                    alert("Ваш браузер не поддерживает загрузку изображений.");
                }
            });
        });
    })();
JS;
$this->registerJs($js);

?>
<div class="content-left">
    <?= $this->render('../common/left-menu') ?>
</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="#">Главная</a> > <a href="#">Редактирование объявления о продаже запчастей</a>
    </div>

    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => Yii::$app->user->identity]) ?>

    <?php
    $addPartForm = ActiveForm::begin([
        'id' => 'update-part-form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);

        echo $addPartForm->field($model, 'brand_id')->hiddenInput()->label(false);
        echo $addPartForm->field($model, 'model_id')->hiddenInput()->label(false);
        ?>
         <div class="update-part-form">
            <div class="form-add">
                <div class="add-block">
                    <div class="wrap">
                        <div class="middle-btn-group select-mark">
                            <div class="select-mark select-large-radius">
                                <a href="#select-mark-pop-up">
                                    <span><?= $model->brand_id ? AutoHelper::getBrandNameById((int)$model->brand_id) : "Выберите марку" ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="middle-btn-group select-model">
                            <div class="select-model select-large-radius">
                                <a href="#select-model-pop-up">
                                    <span><?= $model->model_id ? AutoHelper::getModelNameById((int)$model->model_id) : "Выберите модель" ?></span>
                                </a>
                            </div>
                        </div>

                        <div class="middle-btn-group">
                            <?= $addPartForm->field($model, 'fuel_id')->radioList(AutoHelper::FUEL_TYPES, [
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
                            <?= $addPartForm->field($model, 'engine_volume', [
                                'template' => '<div class="select-large-radius">{input}</div>{error}'
                            ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'Выберите объем двиг.']) ?>
                        </div>
                        <div class="middle-btn-group">
                            <?= $addPartForm->field($model, 'year', [
                                'template' => '<div class="select-large-radius">{input}</div>{error}'
                            ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'Выберите год выпуска']) ?>
                        </div>

                        <div class="middle-btn-group type-body">
                            <?= $addPartForm->field($model, 'body_style')->radioList(AutoHelper::BODY_STYLES, [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                    $return .= '<span>' . $label . '</span>';
                                    $return .= '</div>';
                                    return $return;
                                }
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>

                <div class="title-row">
                    <div class="wrap">
                        <p>Детали</p>
                    </div>
                </div>

                <div class="add-block">
                    <div class="wrap">
                        <div class="content-right-add-part-category">
                            <div class="content-right-add-part-category-list" style="display: inline-block">
                                <?= $addPartForm->field($model, 'category_id', [
                                    'template' => '<div class="select-large-radius">{input}</div>{error}',
                                    'options' => ['tag' => false]
                                ])->dropDownList(PartsCategoryWidget::getCategories(), ['prompt' => 'Категория']) ?>
                            </div>
                            <div style="display: inline-block">
                                <?= $addPartForm->field($model, 'name', [
                                    'template' => '<div class="input-large-radius">{input}</div>{error}',
                                    'options' => ['tag' => false]
                                ])->textInput(['placeholder' => 'Название']) ?>
                            </div>
                            <div style="display: inline-block">
                                <?= $addPartForm->field($model, 'description', [
                                    'template' => '<div class="input-large-radius" style="width:444px">{input}</div>{error}',
                                    'options' => ['tag' => false]
                                ])->textInput(['placeholder' => 'Описание']) ?>
                            </div>
                            <div style="display: inline-block">
                                <?= $addPartForm->field($model, 'price', [
                                    'template' => '<div class="input-small-radius ib" style="width:140px">{input}</div>{error}',
                                    'options' => ['tag' => false]
                                ])->input(['placeholder' => 'Цена']) ?>
                            </div>
                             <div style="display: inline-block">
                                <span>руб</span>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="add-block">
                    <div class="wrap">
                        <div class="photo-block">
                            <div class="photo-block-content">
                                <?php $i = 0; ?>
                                <?php foreach ($adPart->photo as $n => $photo): ?>
                                    <div class="add-part-string">
                                        <div class="photo-btn-delete shown">X</div>
                                        <img src="<?= $adPart->getPhotoUrl($n) ?>">
                                        <div style="display:none;">
                                            <?= $addPartForm->field($model, 'photoUpload[' . $n . ']')->fileInput(['class' => 'photo-input'])->label(false) ?>
                                            <?= Html::activeHiddenInput($model, 'toRemove[' . $n . ']') ?>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                                <?php for ($i; $i <3; $i++ ): ?>
                                    <div class="add-part-string">
                                        <div class="photo-btn-delete">X</div>
                                        <p>Добавить фото</p>
                                        <div style="display:none;">
                                            <?= $addPartForm->field($model, 'photoUpload[' . $i . ']')->fileInput(['class' => 'photo-input'])->label(false) ?>
                                            <?= Html::activeHiddenInput($model, 'toRemove[' . $i . ']') ?>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="add-block">
                    <div class="wrap">
                        <div class="publish-add" onclick="$('#update-part-form').submit()">Сохранить объявление</div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>