<?php
/**
 * Created by PhpStorm.
 * User: pa3py6aka
 * Date: 15.04.17
 * Time: 9:58
 */

namespace app\modules\main\widgets;


use app\board\helpers\PhotoHelper;
use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\helpers\PluralForm;
use app\modules\main\models\Ad;
use app\modules\main\models\AdPart;
use app\modules\main\models\filters\PartsFilter;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

class PartsTableWidget extends Widget
{
    public $cabinet = false;

    /* @var $parts AdPart[] */
    private $parts = null;

    /* @var $pages Pagination */
    private $pages = null;

    /* @var $filter PartsFilter */
    private $filter;

    /* @var $provider ActiveDataProvider */
    private $provider;

    public function init()
    {
        $this->filter = new PartsFilter();
        return parent::init();
    }

    public function run()
    {
        if (!$this->cabinet) {
            return $this->publicTable();
        } else {
            if (Yii::$app->user->isGuest) {
                throw new ForbiddenHttpException();
            }
            return $this->cabinetTable();
        }
    }

    private function publicTable()
    {

        Pjax::begin(['linkSelector' => '.pjax']);
        echo $this->filterBlock();
        ?>
        <div class="select-group-result">
            <table>
                <thead>
                <tr>
                    <th class="photo-column">Фото</th>
                    <th class="detail-column">Название детали</th>
                    <th class="auto-column">Марка и модель</th>
                    <th class="capacity-column">Объем</th>
                    <th class="year-column">Год</th>
                    <th class="body-column">Кузов</th>
                    <th class="city-column">Город</th>
                    <th class="price-column">Цена</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!count($this->getParts())) {
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Ничего не найдено</td>
                    </tr>
                    <?php
                } else {
                    foreach ($this->getParts() as $part) {
                        ?>
                        <tr>
                            <td class="photo-column"><?= $part->photo ? '<img src="' . $part->getPhotoUrl(null) . '" class="img-xs">' : "" ?></td>
                            <td><?= $part->name ?></td>
                            <td><?= $part->autoFullName ?></td>
                            <td><?= $part->engineVolume . " " . $part->getFuelName(true) ?></td>
                            <td><?= $part->yearNormal ?></td>
                            <td><?= $part->bodyStyle ?></td>
                            <td><?= $part->city ?: ($part->user->city ? $part->user->city->name : '') ?></td>
                            <td><?= $part->price ? $part->price . ' руб' : 'Договорная'  ?></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="more-info">
                                <div class="more-info-img gallery">
                                    <?php if ($part->photo) {
                                        $first = true;
                                        foreach ($part->photo as $n => $photo) {
                                            ?>
                                            <a class="gallery-item<?= !$first ? ' hidden' : '' ?>" href="<?= $part->getPhotoUrl($n) ?>" rel="group<?= $part->id ?>">
                                                <img src="<?= $part->getPhotoUrl($n, PhotoHelper::TYPE_LT) ?>" alt="<?= $part->name ?>">
                                            </a>
                                            <?php
                                            $first = false;
                                        }
                                    } else {
                                        echo "Нет фото";
                                    } ?>
                                    <span class="count-images"><?= count($part->photo) ?></span>
                                </div>
                                <div class="more-info-detail-and-descr">
                                    <div class="more-info-title"><?= $part->name ?></div>
                                    <div class="more-info-descr"><?= $part->description ?></div>
                                    <div class="more-info-detail">
                                        <div class="number-add">№ объявление: <span class="number"><?= $part->id ?></span></div>
                                        <div class="views">
                                         <?= $part->views ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="more-info-contact">
                                    <div class="more-info-contact-price">
                                        <p class="price-part"><?= $part->price ? $part->price . ' <span>бел.руб.</span>' : 'Договорная' ?></p>
                                    </div>
                                    <div class="more-info-contact-phone">
                                        <div class="show-phone-link" data-ad-id="<?= $part->id ?>" data-ad-type="<?= $part->type() ?>">показать телефон</div>
                                        <!--<p><?= $part->user->phone_operator ?></p>
                                        <p class="phone-part"><?= $part->user->phone ?></p>-->
                                    </div>
                                    <div class="more-info-contact-time">
                                        <p>Звонить с <?= $part->user->callTimeFrom ?> до <?= $part->user->callTimeTo ?></p>
                                    </div>
                                    <a href="#" class="complain">пожаловаться</a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

            <div class="select-group-result-mobile">
                <?php
                    
                if (!count($this->getParts())) {
                    ?>
                    <div style="text-align: center;">Ничего не найдено</div>
                    <?php
                } else {
                    foreach ($this->getParts() as $part) {
                        ?>
                        <div class="select-group-result-mobile-item">
                            <div class="select-group-result-mobile-item-img"><?= $part->photo ? '<img src="' . $part->getPhotoUrl(null) . '" class="img-xs">' : "" ?></div>
                            <div class="select-group-result-mobile-item-content">
                                <div class="select-group-result-mobile-item-row">
                                    <div class="select-group-result-mobile-item-name"><?= $part->name ?></div>
                                    <div class="select-group-result-mobile-item-price"><?= $part->price ? $part->price . ' руб' : 'Договорная'  ?></div>
                                </div>
                                <div class="select-group-result-mobile-item-row">
                                    <div class="select-group-result-mobile-item-auto"><?= $part->autoFullName ?></div>
                                    <div class="select-group-result-mobile-item-city"><?= $part->city ?: ($part->user->city ? $part->user->city->name : '') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="more-info-mobile">
                            <div class="more-info-mobile-img gallery">
                                <?php if ($part->photo) {
                                    $first = true;
                                    foreach ($part->photo as $n => $photo) {
                                        ?>
                                        <a class="gallery-item<?= !$first ? ' hidden' : '' ?>" href="<?= $part->getPhotoUrl($n) ?>" rel="group<?= $part->id ?>">
                                            <img src="<?= $part->getPhotoUrl($n, PhotoHelper::TYPE_LT) ?>" alt="<?= $part->name ?>">
                                        </a>
                                        <?php
                                        $first = false;
                                    }
                                } else {
                                    echo "Нет фото";
                                } ?>
                                <span class="count-images"><?= count($part->photo) ?></span>
                            </div>
                            <div class="more-info-mobile-details">
                                <div class="more-info-mobile-details-row">
                                    <div class="more-info-mobile-details-title">Название</div>
                                    <div class="more-info-mobile-details-value more-info-mobile-details-value-bold"><?= $part->name ?></div>
                                </div>
                                <div class="more-info-mobile-details-row">
                                    <div class="more-info-mobile-details-title">Объем</div>
                                    <div class="more-info-mobile-details-value"><?= $part->engineVolume . " " . $part->getFuelName(true) ?></div>
                                </div>
                                <div class="more-info-mobile-details-row">
                                    <div class="more-info-mobile-details-title">Год</div>
                                    <div class="more-info-mobile-details-value"><?= $part->yearNormal ?></div>
                                </div>
                                <div class="more-info-mobile-details-row">
                                    <div class="more-info-mobile-details-title">Кузов</div>
                                    <div class="more-info-mobile-details-value"><?= $part->bodyStyle ?></div>
                                </div>
                                <div class="more-info-mobile-details-row">
                                    <div class="more-info-mobile-details-title">Город</div>
                                    <div class="more-info-mobile-details-value"><?= $part->city ?: ($part->user->city ? $part->user->city->name : '') ?></div>
                                </div>                                
                            </div>

                            <p>Описание</p>
                            <div class="more-info-mobile-descr"><?= $part->description ?></div>
                            <div class="more-info-mobile-phone">
                                <div class="show-phone-link" data-ad-id="<?= $part->id ?>" data-ad-type="<?= $part->type() ?>">Показать телефон</div>
                            </div>
                            <div class="more-info-mobile-time">Звонить с <?= $part->user->callTimeFrom ?> до <?= $part->user->callTimeTo ?></div>
                            <div class="more-info-mobile-bottom">
                                <div>№ <span><?= $part->id ?></span></div>
                                <div class="views"><?= $part->views ?></div>
                                <a href="#" class="complain">пожаловаться</a>
                            </div>
                            
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <?php
            if ($this->pages instanceof Pagination && $this->pages->pageCount > 1) {
                echo LinkPager::widget(['pagination' => $this->pages, 'options' => ['class' => 'pagination pages-c']]);
            }
            ?>
            
        </div>
        <?php
        Pjax::end();
    }

    private function filterBlock()
    {
        $partsCategories = AutoHelper::getPartsCategories();
        $this->getParts();
        $sort = $this->provider->sort->attributeOrders;
        ?>

        <?php $filterForm = ActiveForm::begin([
            'action' => ['/main/parts/index'],
            'id' => 'filter-parts-form',
            'method' => 'get',
            'options' => ['data-pjax' => true]
        ]) ?>
        <div class="select-group-mobile">
            <h1>Автозапчасти б/у</h1>
            <button class="select-group-mobile-btn">Открыть фильтр</button>
        </div>
        <div class="select-group-options">
            <div class="wrap">
                <div class="select-group-options-mobile">
                    Фильтр автозапчастей б/у
                    <div class="select-group-options-mobile-close"></div>
                </div>
                <input type="hidden" name="cat" value="<?= $this->filter->cat ?>">
                <input type="hidden" name="brand" value="<?= $this->filter->brand ?>">
                <input type="hidden" name="model" value="<?= $this->filter->model ?>">
                <input type="hidden" name="show" value="0">
                <input type="submit" style="display: none;">

                <!--<form id="select-parts">-->
                <div class="select-group-options-nav">
                    <div class="select-group-options-nav-btn part-list-btn active">Поиск по категориям</div>
                    <div class="select-group-options-nav-btn part-search-btn">Поиск по названию</div>
                </div>
                <h1 class="select-group-options-label">Автозапчасти б/у</h1>
                <div class="select-part-transport">
                    <p>Выберите параметры</p>
                    <div class="select-part-transport-title">Выберите марку</div>    
                    <div class="select-mark select-middle-radius">
                        <a href="#select-mark-pop-up">
                            <span><?= $this->filter->brand ? AutoHelper::getBrandNameById($this->filter->brand) : "Все марки" ?></span>
                        </a>
                    </div>
                    <div class="select-part-transport-title">Выберите модель</div>    
                    <div class="select-model select-middle-radius">
                        <a href="#select-model-pop-up">
                            <span><?= $this->filter->model ? AutoHelper::getModelNameById($this->filter->model) : "Все модели" ?></span>
                        </a>
                    </div>
                </div>
                <div class="select-list-title">Выберите категорию автозапчастей</div>
                <ul class="select-part-list">
                    <?php
                    foreach ($partsCategories as $category) {
                        ?>
                        <li class="select-part-item <?= ($category['id'] == 1 ? 'show-dop-list ' : '') . ($this->filter->cat == $category['id'] || ($category['id'] == 1 && $this->filter->sub_cats) ? 'active' : '')?>" data-id="<?= $category['id'] ?>">
                            <a href="javascript:void(0)"><?= $category['title'] ?><span class="count"><?= $this->filter->getCount('category_id', $category['id']) ?></span></a>
                            <div class="part-tooltip">
                                <p>В этом разделе размещают:</p>
                                <ul class="part-tooltip-list <?= (count($category['tooltip']) > 20 ? 'column-count-3 ' : (count($category['tooltip']) > 10 ? 'column-count-2 ' : ''))?>">   
                                    <?php
                                    foreach ($category['tooltip'] as $value) {
                                        ?>
                                        <li class="part-tooltip-list-item">- <?= $value?></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

                <div class="select-part-list-dop"<?= $this->filter->sub_cats || Yii::$app->request->get('show') ? ' style="display:block;"' : '' ?>>
                    <div class="select-part-list-wrap">
                        <?php
                        $engineCatId = array_search(1, array_column($partsCategories, 'id'));
                        foreach ($partsCategories[$engineCatId]['sub_categories'] as $cat) {
                            ?>
                            <div class="list-dop<?= in_array($cat['id'], $this->filter->sub_cats) ? ' active' : '' ?>">
                                <input type="checkbox" name="sub_cats[]" value="<?= $cat['id'] ?>"<?= in_array($cat['id'], $this->filter->sub_cats) ? ' checked' : '' ?> id="checkbox_<?= $cat['id'] ?>">
                                <label for="checkbox_<?= $cat['id'] ?>"><?= $cat['title'] ?><span class="count"><?= $this->filter->getCount('category_id', $cat['id']) ?></span></label>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="select-part-search">
                    <div class="input-large-radius">
                        <input type="text" placeholder="Введите название для запчасти">
                    </div>
                    <button class="select-part-search-btn" type="submit">Найти</button>
                </div>
            </div>
            <div class="select-group-options-toggle">
                <div class="show-search-field">Поиск запчастей</div>
                <div class="hide-search-field">Вернуться у фильтру запчастей</div>
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
        <?php ActiveForm::end(); ?>
        <?php
        $this->view->registerJs($this->getJs());
    }

    private function cabinetTable()
    {
        $parts = $this->getParts();
        $partsByAuto = [];
        foreach ($parts as $part) {
            $key = $part->brand_id . "-" . $part->model_id . "-" . $part->fuel_id . "-" . $part->engine_volume . "-" . $part->body_style . "-" . $part->year;
            $partsByAuto[$key][] = $part;
        }

        if (count($partsByAuto)): ?>
        <div class="parts-adverts category-block">
            <div class="category-title">Автозапчасти Б/У</div>
            <div class="prolongue-group-btn ad-prolong-link" data-ad-type="<?= Ad::TYPE_PART ?>" data-group="1">Продлить все объявления группы</div>
            <div class="category-list">
                <?php
                $currentAuto = null;
                foreach ($partsByAuto as $autoParts) {
                    /* @var $autoParts AdPart[] */
                    $partsIds = [];
                    ?>
                    <div class="category-advert">
                        <div class="add-part-this">
                            <a href="<?= Url::to([
                                '/main/parts/add',
                                'b' => $autoParts[0]->brand_id,
                                'm' => $autoParts[0]->model_id,
                                'f' => $autoParts[0]->fuel_id,
                                'e' => $autoParts[0]->engine_volume,
                                'y' => $autoParts[0]->year,
                                's' => $autoParts[0]->body_style
                            ]) ?>">+ добавить к этой модели запчасти</a>
                        </div>
                        <div class="category-advert-table">
                            <table>
                                <thead>
                                <tr>
                                    <th class="auto"><?= $autoParts[0]->autoFullName ?></th>
                                    <th class="descr">
                                        <?= $autoParts[0]->engineVolume . "" . $autoParts[0]->getFuelName(true) . " " . $autoParts[0]->year . "  " . $autoParts[0]->bodyStyle ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($autoParts as $part) {
                                    $partsIds[] = $part->id;
                                    ?>
                                    <tr>
                                        <td class="title-part">
                                            <?= $part->name ?>
                                            <div class="category-advert-extend-text">
                                                <p><?= AdHelper::activeTimeString($part) ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="state"><?= $part->description ?></span>
                                            <span class="phone"><?= $part->user->phone ?></span>
                                            <span class="photo">
                                                <?php if ($part->photo) {
                                                    ?>
                                                    <a class="gallery-item" href="<?= $part->photoUrl ?>">
                                                        <img src="<?= $part->getPhotoUrl(0, PhotoHelper::TYPE_LK) ?>" alt="<?= $part->name ?>">
                                                    </a>
                                                    <?php
                                                } else {
                                                    echo "Нет фото";
                                                } ?>
                                            </span>
                                            <span class="price"><?= $part->price ? $part->price . ' руб' : 'Договорная' ?></span>
                                            <span class="city"><?= $part->city ?: $part->user->city->name ?></span>
                                            <span class="edit-advert tooltip-has" data-part-id="<?= $part->id ?>"><a
                                                        href="<?= Url::to(['/main/parts/update', 'id' => $part->id]) ?>"></a>
                                                <span class="tooltip-popup">редактировать</span>
                                            </span>
                                            <span class="delete-advert tooltip-has" data-part-id="<?= $part->id ?>"><a
                                                        href="#"></a>
                                                <span class="tooltip-popup">удалить</spandiv>    
                                            </span>
                                            <span class="views"><?= $part->views; /*PluralForm::get($part->views, "просмотр", "просмотра", "просмотров")*/ ?></span>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="category-advert-extend-wrap">
                            <div class="category-advert-extend parts-table">
                                <a
                                        href="javascript:void(0)"
                                        class="category-advert-extend-btn ad-prolong-link"
                                        data-ad-type="<?= Ad::TYPE_PART ?>"
                                        data-ad-id="<?= implode(",", $partsIds) ?>"
                                >Продлить</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
                if ($this->pages instanceof Pagination && $this->pages->pageCount > 1) {
                    echo LinkPager::widget(['pagination' => $this->pages, 'options' => ['class' => 'pagination pages-c']]);
                }
                ?>
            </div>
        </div>
        <?php endif;
    }

    /**
     * @return \app\modules\main\models\AdPart[]|array|null
     */
    private function getParts()
    {
        if ($this->parts === null) {
            $query = AdPart::find()
                ->with(['model', 'brand', 'user.city']);
            if (!$this->cabinet) {
                $query->active();
            } else {
                $query->where(['not', ['status' => AdPart::STATUS_DELETED]])
                    ->andWhere(['user_id' => Yii::$app->user->id])
                    ->orderBy(['brand_id' => SORT_ASC, 'model_id' => SORT_ASC]);
            }

            $provider = $this->filter->search($query);
            $this->provider = $provider;
            $this->parts = $provider->getModels();
            $this->pages = $provider->getPagination();
        }

        return $this->parts;
    }

    private function getJs()
    {
        $loadModels = $this->filter->brand ? "AutoBrest.loadModels(" . $this->filter->brand . ");" : "";
        //$showDopList = $this->filter->sub_cats ? "$('.select-part-list-dop').show();$('.show-dop-list[data-id=1]').addClass('active');" : "";
        return <<<JS
            window.addEventListener('load', function() {
                var filterForm = $("#filter-parts-form");
                
                $(document).on("click", ".select_mark_pop-up_content ul li a", function(){
                    $("#filter-parts-form").find("input[name=brand]").val($(this).attr("data-id"));
                    $("#filter-parts-form").find("input[name=model]").val('');
                    updateParts();
                });
                $(document).on("click", ".select_model_pop-up_content ul li a", function(){
                    $("#filter-parts-form").find("input[name=model]").val($(this).attr("data-model-id"));
                    updateParts();
                });
                $(".select_mark_pop-up_content .all_marks").click(function() {
                    var filterForm = $("#filter-parts-form");
                    filterForm.find("input[name=brand]").val('');
                    filterForm.find("input[name=model]").val('');
                    updateParts();
                    $.magnificPopup.close();
                });
                $("button#all_models_btn").on('click' ,function() {
                    var filterForm = $("#filter-parts-form");
                    filterForm.find("input[name=model]").val('');
                    updateParts();
                    $.magnificPopup.close();
                });
                $(document).on('click', '.select-part-list li', function() {
                    var cat = $(this).hasClass('active') ? '' : $(this).attr("data-id");
                    $("#filter-parts-form").find("input[name=cat]").val(cat);
                    if ($(this).attr("data-id") != '1') {
                        $('.select-part-list-dop input[type=checkbox]').prop('checked', false);
                        updateParts();
                    } else {
                        if ($(this).hasClass("active")) {
                            $(this).removeClass("active");
                            $('.select-part-list-dop input[type=checkbox]').prop('checked', false);
                            updateParts();
                        } else {
                            $(".select-part-list").find("li.active").removeClass("active");
                            $(this).addClass("active");
                        }
                    }
                });
                $(document).on("change", ".select-part-list-dop input[type=checkbox], select[name=sort]", function () {
                    $("#filter-parts-form").find("input[name=show]").val('1');
                    updateParts();
                });
              
                function updateParts() {
                    $("#filter-parts-form").submit();
                };
                
                {$loadModels}
            });
JS;
    }
}