<?php

/* @var $this yii\web\View */
/* @var $adWheels \app\modules\main\models\AdWheel[] */
/* @var $filter \app\modules\main\models\filters\WheelsFilter */
/* @var $pagination \yii\data\Pagination */
/* @var $sort array */

use app\helpers\AutoHelper;
use app\modules\main\widgets\UrgentSaleWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

?>

<?= $this->render("../common/left-content"); ?>

<div class="content-right">
        <div class="breadcrumbs">
            <a href="/">Главная</a> > <a href="javascript:void(0);">Диски</a>
        </div>
        <div class="group-content">
            <div class="group-content-img">
                <a href="#"></a>
            </div>
            <?= $this->render('../common/categories-menu') ?>

            <?php Pjax::begin(['linkSelector' => '.pjax']); ?>
            <?php $filterForm = ActiveForm::begin([
                'action' => ['/main/wheels/index'],
                'id' => 'select-wheels',
                'method' => 'get',
                'options' => ['data-pjax' => true]
            ]);
                echo $filterForm->field($filter, 'auto_brand')->hiddenInput(['id' => 'addwheelform-auto-brand-id'])->label(false) ?>
                <div class="select-group-mobile">
                    <h1>Диски</h1>
                    <button class="select-group-mobile-btn">Открыть фильтр</button>
                </div>
                <div class="select-group-options">
                    <div class="inner-wrap">
                        <div class="select-group-options-mobile">
                            Фильтр дисков
                            <div class="select-group-options-mobile-close"></div>
                        </div>
                        <?= $filterForm->field($filter, 'auto_type', [
                            'template' => '<div class="select-part-transport">{input}</div>',
                            'options' => ['tag' => false]
                        ])->radioList(AutoHelper::getWheelAutoArray(), [
                            'item' => function($index, $label, $name, $checked, $value) use($filter) {
                                $return = '<label class="active">';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                $return .= '<span class="label-wrap"><span class="label-inner">' . $label . ' диски<span class="count">' . $filter->getCount('wheel_auto', $value) . '</span></span></span>';
                                $return .= '</label>';
                                return $return;
                            }
                        ]) ?>

                        <div class="select-part-transport">
                            <div class="select-part-transport-column ib">
                                <div class="select-part-transport-item">
                                    <div class="select-part-transport-title">Диски для</div>
                                    <div class="select-mark select-middle-radius">
                                        <a href="#select-mark-pop-up">
                                            <span><?= $filter->auto_brand ? AutoHelper::getBrandNameById($filter->auto_brand) : "Все" ?></span>
                                        </a>
                                    </div>
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
                                    <div class="select-part-transport-title">Выберите тип дисков</div>
                                    <?= $filterForm->field($filter, 'wheel_type', [
                                        'template' => '<div class="select-middle-radius">{input}</div>'
                                    ])->dropDownList(AutoHelper::getWheelTypesArray(), ['prompt' => 'Все']) ?>
                                </div>
                                <div class="select-part-transport-item">
                                    <div class="select-part-transport-title">Количество болтов</div>
                                    <?= $filterForm->field($filter, 'bolts', [
                                    'template' => '<div class="select-middle-radius">{input}</div>'
                                    ])->dropDownList(AutoHelper::getWheelBoltsArray(), ['prompt' => 'Все']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="select-part-transport amount">
                            <div class="select-part-transport-title">Кол-во дисков</div>
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
                        <div class="select-part-transport state">
                            <?= $filterForm->field($filter, 'is_new', [
                                'template' => '{input}',
                                'options' => ['tag' => false],
                            ])->checkboxList([1 => 'новые', 0 => 'с пробегом'], [
                                'item' => function($index, $label, $name, $checked, $value) use($filter) {
                                    $return = '<label class="block-radio' . ($checked ? ' active' : '') . '">';
                                    $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                                    $return .= '<span> ' . $label . '<span class="count">' . $filter->getCount('is_new', $value) . '</span></span>';
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
                            <a href="<?= Url::to(['/main/wheels/filter']) ?>">Показать полный фильтр</a>
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
                    <?php foreach ($adWheels as $adWheel) : ?>
                        <?= $this->render("wheel_item", ['adWheel' => $adWheel]) ?>
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
        $(document).on("change", "#select-wheels select, #select-wheels input", function() {
            updateFilter();
        });
        
        $(document).on("click", ".select_mark_pop-up_content ul li a", function(){
		    $("form#select-wheels").find("#addwheelform-auto-brand-id").val($(this).attr('data-id'));
            updateFilter();
        });
        $(".select_mark_pop-up_content .all_marks").click(function() {
            var filterForm = $("#select-wheels");
            filterForm.find("input[name*=auto_brand]").val('');
            updateFilter();
            $.magnificPopup.close();
        });
        
        $(document).on("change", ".group-sort select[name=sortwheel]", function() {
            var attribute = $(this).val(),
                sort = "";
            if (attribute == "price_asc") {
                sort = "?sort=price";
            } else if (attribute == "price_desc") {
                sort = "?sort=-price";
            }
            
            $("#select-wheels").attr("action", "/wheels" + sort);
            updateFilter();
        });
        
        function updateFilter() {
            $("form#select-wheels").submit();
        }
    })(jQuery);
JS;
$this->registerJs($js);