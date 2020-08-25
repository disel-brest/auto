<?php

/* @var $this yii\web\View */
/* @var $adCars \app\modules\main\models\AdCar[] */
/* @var $filter \app\modules\main\models\filters\CarsFilter */
/* @var $pagination \yii\data\Pagination */
/* @var $sort array */

use app\components\Currency;
use app\helpers\AutoHelper;
use app\modules\main\widgets\UrgentSaleWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<?= $this->render("../common/left-content"); ?>

<div class="content-right">
    <div class="breadcrumbs">
        <a href="/">Главная</a> > <a href="#">Частные объявления</a> > <a href="#">Продажа транспорта</a> > <a href="#">Продажа легковых авто</a>
    </div>
    <div class="group-content">
        <?= $this->render('../common/categories-menu') ?>

        <?php Pjax::begin(['linkSelector' => '.pjax']); ?>
        <div class="select-group-options">
            <?php $filterForm = ActiveForm::begin([
                'action' => ['/main/cars/index'],
                'id' => 'select-cars',
                'method' => 'get',
                'options' => ['data-pjax' => true]
            ]) ?>
                <?= $filterForm->field($filter, 'brand')->hiddenInput()->label(false) ?>
                <?= $filterForm->field($filter, 'model')->hiddenInput()->label(false) ?>
                <input type="hidden" name="sort">

                <input type="submit" style="display: none;">

                <div class="search-transport-column">
                    <div class="select-mark select-middle-radius">
                        <a href="#select-mark-pop-up">
                            <span><?= $filter->brand ? AutoHelper::getBrandNameById($filter->brand) : "Марка" ?></span>
                        </a>
                    </div>
                    <div class="select-model select-middle-radius">
                        <a href="#select-model-pop-up">
                            <span><?= $filter->model ? AutoHelper::getModelNameById($filter->model) : "Модель" ?></span>
                        </a>
                    </div>

                    <?= $filterForm->field($filter, 'bodyStyle', [
                        'template' => '<div class="select-middle-radius">{input}</div>'
                    ])->dropDownList(AutoHelper::BODY_STYLES, ['prompt' => 'Тип кузова']) ?>

                </div>
                <div class="search-transport-column">
                    <div class="input-middle-radius">
                        <?= $filterForm->field($filter, 'year_min', [
                            'options' => ['tag' => false],
                            'template' => '<div class="select-small">{input}</div>'
                        ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'Год от', 'class' => '']) ?>
                        <?= $filterForm->field($filter, 'year_max', [
                            'options' => ['tag' => false],
                            'template' => '<div class="select-small">{input}</div>'
                        ])->dropDownList(AutoHelper::getYearsArray(), ['prompt' => 'До', 'class' => '']) ?>
                    </div>

                    <?= $filterForm->field($filter, 'drivetrain', [
                        'template' => '<div class="select-type-drive-transport"><p>Тип привода</p>{input}</div>',
                    ])->checkboxList(AutoHelper::DRIVETRAIN_TYPES, [
                        'item' => function($index, $label, $name, $checked, $value) use ($filter) {
                            $return = '<label>';
                            $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $label;
                            $return .= '<span class="count"> ' . $filter->getCount('drivetrain', $value) . '</span>';
                            $return .= '</label>';
                            return $return;
                        }
                    ]) ?>
                </div>
                <div class="search-transport-column">
                    <div class="input-middle-radius">
                        <?= $filterForm->field($filter, 'engineVolume_min', [
                            'options' => ['tag' => false],
                            'template' => '<div class="select-small">{input}</div>'
                        ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'Объем от', 'class' => '']) ?>
                        <?= $filterForm->field($filter, 'engineVolume_max', [
                            'options' => ['tag' => false],
                            'template' => '<div class="select-small">{input}</div>'
                        ])->dropDownList(AutoHelper::ENGINE_VOLUMES, ['prompt' => 'До', 'class' => '']) ?>
                    </div>

                    <?= $filterForm->field($filter, 'fuel', [
                        'template' => '<div class="select-type-engine-transport"><p>Тип двигателя</p>{input}</div>',
                    ])->checkboxList(AutoHelper::FUEL_TYPES, [
                        'item' => function($index, $label, $name, $checked, $value) use ($filter) {
                            $return = '<label>';
                            $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $label;
                            $return .= '<span class="count"> ' . $filter->getCount('fuel_id', $value) . '</span>';
                            $return .= '</label>';
                            return $return;
                        }
                    ]) ?>
                </div>
                <div class="search-transport-column">
                    <div class="input-middle-radius">
                        <?= $filterForm->field($filter, 'price_min', [
                            'options' => ['tag' => false],
                            'template' => '<div class="select-small">{input}</div>'
                        ])->dropDownList(AutoHelper::getPriceArray(), ['prompt' => 'Цена от', 'class' => '']) ?>
                        <?= $filterForm->field($filter, 'price_max', [
                            'options' => ['tag' => false],
                            'template' => '<div class="select-small">{input}</div>'
                        ])->dropDownList(AutoHelper::getPriceArray(), ['prompt' => 'До', 'class' => '']) ?>
                    </div>

                    <?= $filterForm->field($filter, 'transmission', [
                        'template' => '<div class="select-type-gear-transport"><p>Коробка передач</p>{input}</div>',
                    ])->checkboxList(AutoHelper::TRANSMISSION_TYPES, [
                        'item' => function($index, $label, $name, $checked, $value) use ($filter) {
                            $return = '<label>';
                            $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $label;
                            $return .= '<span class="count"> ' . $filter->getCount('transmission', $value) . '</span>';
                            $return .= '</label>';
                            return $return;
                        }
                    ]) ?>
                </div>
                <div class="search-transport-column last">

                    <?= $filterForm->field($filter, 'currency', [
                        'options' => ['tag' => false],
                        'template' => '<div class="select-small-radius">{input}</div>'
                    ])->dropDownList(Currency::getCurrenciesArray(), ['class' => '']) ?>

                    <div class="select-dop-options-transport">

                        <?= $filterForm->field($filter, 'change', [
                            'options' => ['tag' => false],
                        ])->checkbox([
                            'template' => '<div class="select-exchange-transport"><label>{input} {labelTitle} <span class="count">' . $filter->getCount('change', 1) . '</span></label></div>'
                        ]) ?>

                        <?= $filterForm->field($filter, 'lawFirm', [
                            'options' => ['tag' => false],
                        ])->checkbox([
                            'template' => '<div class="select-payment-type-transport"><label>{input} {labelTitle} <span class="count">' . $filter->getCount('law_firm', 1) . '</span></label></div>'
                        ]) ?>

                    </div>
                </div>
                <div class="show-full-filter">
                    <a href="<?= Url::to(['/main/cars/filter']) ?>">Показать полный фильтр</a>
                </div>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="group-sort">
            <p>Сортировать по</p>
            <select name="sortcar" id="sortgroupby">
                <option value="date_desc"<?= isset($sort['id']) ? " selected" : "" ?>>Дате подачи</option>
                <option value="price_asc"<?= isset($sort['price']) && $sort['price'] == SORT_ASC ? " selected" : "" ?>>Цене (возрастанию)</option>
                <option value="price_desc"<?= isset($sort['price']) && $sort['price'] == SORT_DESC ? " selected" : "" ?>>Цене (убыванию)</option>
            </select>
        </div>
        <div class="select-group-result">
            <div class="group-adverts-container">
                <?php foreach ($adCars as $adCar) : ?>
                    <?= $this->render("car_item", ['adCar' => $adCar]) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="pagination">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>

<?= UrgentSaleWidget::widget() ?>

<?php
$js = <<<JS
    (function() {
        $(document).on("click", ".select_mark_pop-up_content ul li a", function(){
		    $("form#select-cars").find("input#carsfilter-brand").val($(this).attr('data-id'));
            updateCars();
        });
        $(document).on("click", ".select_model_pop-up_content ul li a", function(){
            $("form#select-cars").find("input#carsfilter-model").val($(this).attr('data-model-id'));
            updateCars();
        });
        $(document).on("change", "#carsfilter-bodystyle", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-year_min", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-year_max", function() {
            updateCars();
        });
        $(document).on("change", ".select-type-drive-transport input[type=checkbox]", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-enginevolume_min", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-enginevolume_max", function() {
            updateCars();
        });
        $(document).on("change", ".select-type-engine-transport input[type=checkbox]", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-price_min", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-price_max", function() {
            updateCars();
        });
        $(document).on("change", ".select-type-gear-transport input[type=checkbox]", function() {
            updateCars();
        });
        $(document).on("change", "#carsfilter-currency", function() {
            if ($("#carsfilter-price_min").val() || $("#carsfilter-price_max").val()) {
                updateCars();
            }
        });
        $(document).on("change", "#carsfilter-change", function() {
            updateCars();
        })
        $(document).on("change", "#carsfilter-lawfirm", function() {
            updateCars();
        })
        
        $(document).on("change", ".group-sort select[name=sortcar]", function() {
            var attribute = $(this).val(),
                sort = "";
            if (attribute == "price_asc") {
                sort = "?sort=price";
            } else if (attribute == "price_desc") {
                sort = "?sort=-price";
            }
            
            $("#select-cars").attr("action", "/cars" + sort);
            updateCars();
        });
        
        function updateCars() {
            $("form#select-cars").submit();
        }
    })(jQuery);
JS;
$this->registerJs($js);