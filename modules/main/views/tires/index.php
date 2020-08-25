<?php

/* @var $this yii\web\View */
/* @var $adTires \app\modules\main\models\AdTire[] */
/* @var $filter \app\modules\main\models\filters\TiresFilter */
/* @var $pagination \yii\data\Pagination */
/* @var $sort array */

use app\components\Currency;
use app\helpers\AutoHelper;
use app\modules\main\models\TireBrand;
use app\modules\main\models\TireModel;
use app\modules\main\widgets\UrgentSaleWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

?>

<?= $this->render("../common/left-content"); ?>

<div class="content-right">
        <div class="breadcrumbs">
            <a href="/">Главная</a> > <a href="javascript:void(0);">Шины</a>
        </div>
        <div class="group-content">
            <div class="group-content-img">
                <a href="#"></a>
            </div>
            <?= $this->render('../common/categories-menu') ?>

            <?php Pjax::begin(['linkSelector' => '.pjax', 'formSelector' => '#select-tires', 'enablePushState' => true]); ?>
            <?php $filterForm = ActiveForm::begin([
                'action' => ['/main/tires/index'],
                'id' => 'select-tires',
                'method' => 'get',
                'options' => ['data-pjax' => true]
            ]) ?>
            <div class="select-group-mobile">
                <h1>Шины</h1>
                <button class="select-group-mobile-btn">Открыть фильтр</button>
            </div>
            <div class="select-group-options">
                    <div class="inner-wrap">
                        <div class="select-group-options-mobile">
                            Фильтр шин
                            <div class="select-group-options-mobile-close"></div>
                        </div>
                        <?= $filterForm->field($filter, 'tire_type', [
                            'template' => '<div class="select-part-transport">{input}</div>',
                            'options' => ['tag' => false]
                        ])->radioList(AutoHelper::getTireTypesArray(), [
                            'item' => function($index, $label, $name, $checked, $value) use($filter) {
                                $return = '<label class="active">';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                $return .= '<span class="label-wrap"><span class="label-inner">' . $label . ' шины<span class="count">' . $filter->getCount('tire_type', $value) . '</span></span></span>';
                                $return .= '</label>';
                                return $return;
                            }
                        ]) ?>

                        <div class="select-part-transport">
                            <div class="select-part-transport-column ib">
                                <div class="select-part-transport-item">
                                    <div class="select-part-transport-title">Производитель</div>
                                    <?= $filterForm->field($filter, 'brand', [
                                        'template' => '<div class="select-middle-radius">{input}</div>'
                                    ])->dropDownList(TireBrand::itemsArray(), ['prompt' => 'Все']) ?>
                                </div>
                                <div class="select-part-transport-item">
                                    <div class="select-part-transport-title">Радиус</div>
                                    <?= $filterForm->field($filter, 'radius', [
                                        'template' => '<div class="select-middle-radius">{input}</div>'
                                    ])->dropDownList(AutoHelper::getTireRadiusArray(), ['prompt' => 'Все']) ?>
                                </div>
                            </div>
                            <div class="select-part-transport-column ib">
                                <div class="select-part-transport-item">
                                    <div class="select-part-transport-title">Модель</div>
                                    <?= $filterForm->field($filter, 'model', [
                                        'template' => '<div class="select-middle-radius">{input}</div>'
                                    ])->dropDownList(TireModel::getModelsByBrand($filter->brand), ['prompt' => 'Все']) ?>
                                </div>
                                <div class="select-part-transport-item">
                                    <div class="select-part-transport-title ib">Ширина</div>
                                    <div class="select-part-transport-title ib">Высота</div>
                                    <div class="middle-radius">
                                        <?= $filterForm->field($filter, 'width', [
                                            'template' => '<div class="select-small ib">{input}</div>',
                                            'options' => ['tag' => false],
                                        ])->dropDownList(AutoHelper::getTireWidthArray(), ['class' => '', 'prompt' => 'Все']) ?>

                                        <?= $filterForm->field($filter, 'aspect_ratio', [
                                            'template' => '<div class="select-small ib">{input}</div>',
                                            'options' => ['tag' => false],
                                        ])->dropDownList(AutoHelper::getTireAspectRatioArray(), ['class' => '', 'prompt' => 'Все']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="select-part-transport state">
                                <?= $filterForm->field($filter, 'is_new', [
                                    'template' => '{input}',
                                    'options' => ['tag' => false],
                                ])->checkboxList([1 => 'Новые', 0 => 'С пробегом'], [
                                    'item' => function($index, $label, $name, $checked, $value) use($filter) {
                                        $return = '<label class="block-radio ib' . ($checked ? ' active' : '') . '">';
                                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                        $return .= '<span> ' . $label . '<span class="count">' . $filter->getCount('is_new', $value) . '</span></span>';
                                        $return .= '</label>';
                                        return $return;
                                    }
                                ]) ?>
                            </div>
                        </div>
                        <div class="select-part-transport season-block">
                            <div class="select-part-transport-title">Тип</div>
                            <?= $filterForm->field($filter, 'season', [
                                'template' => '{input}',
                                'options' => ['tag' => false],
                            ])->checkboxList(AutoHelper::getTireSeasonsArray(), [
                                'item' => function($index, $label, $name, $checked, $value) use($filter) {
                                    $return = '<label class="block-radio' . ($checked ? ' active' : '') . '">';
                                    $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                    $return .= '<span> ' . $label . '<span class="count">' . $filter->getCount('season', $value) . '</span></span>';
                                    $return .= '</label>';
                                    return $return;
                                }
                            ]) ?>
                        </div>
                        <div class="select-part-transport amount-block">
                            <div class="select-part-transport-title">Кол-во шин</div>
                            <?= $filterForm->field($filter, 'amount', [
                                'template' => '{input}',
                                'options' => ['tag' => false],
                            ])->checkboxList(AutoHelper::getTireAmountArray(), [
                                'item' => function($index, $label, $name, $checked, $value) use($filter) {
                                    $return = '<label class="block-radio' . ($checked ? ' active' : '') . '">';
                                    $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                    $return .= '<span> ' . $label . '<span class="count">' . $filter->getCount('amount', $value) . '</span></span>';
                                    $return .= '</label>';
                                    return $return;
                                }
                            ]) ?>
                        </div>
                        <div class="select-group-options-btn">
                            <div class="select-group-options-mobile-close">Показать: 32</div>
                        </div>

                        <?php /*
                        <div class="show-full-filter">
                            <a href="<?= Url::to(['/main/tires/filter']) ?>">Показать полный фильтр</a>
                        </div> */ ?>

                    </div>

            </div>
            <div class="group-sort">
                <div class="wrap">
                    <p>Сортировать по</p>
                    <div class="sort-trigger">
                        <select name="sort" id="sortgroupby">
                            <option value="id"<?= isset($sort['id']) ? " selected" : "" ?>>Дате подачи</option>
                            <option value="price"<?= isset($sort['price']) && $sort['price'] == SORT_ASC ? " selected" : "" ?>>Цене (возрастанию)</option>
                            <option value="-price"<?= isset($sort['price']) && $sort['price'] == SORT_DESC ? " selected" : "" ?>>Цене (убыванию)</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end() ?>

            <div class="select-group-result">
                <div class="group-adverts-container">
                    <?php foreach ($adTires as $adTire) : ?>
                        <?= $this->render("tire_item", ['adTire' => $adTire]) ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="pagination">
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>

<!--<?= UrgentSaleWidget::widget() ?>-->

<?php
$js = <<<JS
    (function() {
        $(document).on("change", "#select-tires select, #select-tires input", function() {
            updateFilter();
        });
        
        function updateFilter() {
            $("form#select-tires").submit();
        }
    })(jQuery);
JS;
$this->registerJs($js);