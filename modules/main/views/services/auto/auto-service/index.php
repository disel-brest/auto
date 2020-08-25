<?php
use app\board\helpers\AutoServiceHelper;
use app\board\helpers\AutoServiceWorkHelper;
use app\helpers\PluralForm;
use app\modules\main\widgets\AutoServicesCategoriesListWidget;
use yii\bootstrap\Html;
use yii\widgets\LinkPager;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $count integer */
/* @var $work \app\board\entities\AutoServiceWork|null */

//$this->registerCssFile("@web/css/sputnik_maps_full.css");
echo Html::cssFile('/css/sputnik_maps_full.css');
$this->registerJsFile("@web/js/sputnik_maps_full.js");

?>

<div class="content-left">
    <div class="content-left-photo">
        <div class="content-left-photo-title">
            <p>Автосервисы</p>
            <p><?= PluralForm::get($count, 'предложение', 'предложения', 'предложений') ?></p>
        </div>
        <div class="add-advert-btn">
            <a href="#">Добавить свой сервис</a>
        </div>
    </div>
    <div class="content-left-menu">
        <?= AutoServicesCategoriesListWidget::widget(['work_id' => $work ? $work->id : null]) ?>
    </div>
</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="/">Главная</a> > <a href="#">Услуги</a> > <a href="#">Авто</a> > <a href="#">Автосервисы</a>
    </div>
    <div class="service-content">
        <div class="service-content-top">
            <img src="<?= AutoServiceWorkHelper::categoryPhoto($work) ?>" alt="">
            <div class="service-content-tabs">
                <div class="service-content-title"><?= $work ? $work->name : "Все сервисы" ?></div>
                <div class="tabs">
                    <div class="tab active">
                        <div class="tab_title" id="listTabLink">Списком</div>
                    </div>
                    <div class="tab">
                        <div class="tab_title" id="mapTabLink">На карте</div>
                    </div>
                </div>
            </div>                
        </div>
        <div class="service-container">
            <div class="tab_content">
                <div class="tab_item">
                    <div class="service-list">
                        <?php foreach ($dataProvider->getModels() as $autoService) : ?>
                            <?php /* @var $autoService \app\board\entities\AutoService */ ?>
                            <?php //$service = $autoService; ?>
                            <div class="service-item">
                                <div class="service-item-top">
                                    <div class="service-item-contacts">
                                        <div class="service-item-title"><span><?= $autoService->name ?></span><?= $autoService->sub_text ?></div>
                                        <div class="service-item-address"><?= $autoService->city->name ?>, <?= $autoService->street ?></div>
                                    </div>
                                    <div class="service-item-info">
                                        <ul>
                                            <!--В будещем здесь скорее всего будут ещё другие пункты-->
                                            <li class="service-item-info-point"><?= $autoService->year ?> год</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="service-item-gallery clearfix">
                                <?php $i = 0; ?>
                                <?php foreach ($autoService->getPhotos() as $photo) : ?>
                                    <div class="service-item-photo">
                                        <img src="<?= AutoServiceHelper::filesPath() . "/" . $photo ?>" alt="">
                                    </div>
                                    <?php
                                    $i++;
                                    if ($i == 4) { break; }
                                    ?>
                                <?php endforeach; ?>
                                </div>
                                <div class="service-item-bottom">
                                    <div class="service-item-about">
                                        <?= $autoService->info ?>
                                    </div>
                                    <div class="service-item-more">
                                        <a href="<?= \yii\helpers\Url::to(['view', 'id' => $autoService->id]) ?>">Подробнее</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tab_item" id="tab-map">
                    <div class="service-map" id="service-map" style="height: 500px;width:972px;"></div>
                </div>
            </div>
        </div>
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination
        ]) ?>
        <!--<div class="pagination">
            <ul>
                <li><a href="#"><</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">...</a></li>
                <li><a href="#">7</a></li>
                <li><a href="#">></a></li>
            </ul>
        </div>-->
    </div>
</div>
<?php
$lat = isset($autoService) && $autoService ? $autoService->lat : 52.092874;
$lng = isset($autoService) && $autoService ? $autoService->lng : 23.691610;
$workID = $work ? $work->id : '';
$mapUpdateUrl = \yii\helpers\Url::to(['map-update']);
$js = <<<JS
function setMap() {
    function dataFormatter(response)
{
	var geoJson = {
			"type": "FeatureCollection",
			"features": []
		},
		elements = response.elements || [];

	function formatForGeoJson(data)
	{
		geoJson.features.push({
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [data.lon, data.lat]
			},
			"properties": {
				"title": data.name + ':' + data.info
			}
		});
	}

	for (var i = 0; i < elements.length; i ++) {
		formatForGeoJson(elements[i]);
	}

	return geoJson;
}
var sm = L.sm(),
	map = sm.map('service-map', {
		zoomControl: true,
		minZoom: 3,
		maxZoom: 19,
		themePath: '/dist/themes/sputnik_maps/'
	}).setView([{$lat}, {$lng}], 10);
    sm.updateMarkersFromServer(map, {
        url: '{$mapUpdateUrl}',
        method: "POST",
        params: {swlat:'SW_LAT',swlng:'SW_LNG',nelat:'NE_LAT',nelng:'NE_LNG', work_id:'{$workID}'},
        dataFormatter: dataFormatter,
        searchOnStart: true
    }, {markerType: 'default', cluster: {maxClusterRadius: 20}});
}

(function ($) {
	  $.each(['show', 'hide'], function (i, ev) {
	    var el = $.fn[ev];
	    $.fn[ev] = function () {
	      this.trigger(ev);
	      return el.apply(this, arguments);
	    };
	  });
	})(jQuery);
var isShown = false;
$("#tab-map").on("show", function() {
    if (!isShown) {
        setTimeout(setMap, 100);
        isShown = true;
    }
});
if (document.location.hash === "#map" ) {
    $("#mapTabLink").parent().click();
    setServicesMapLinks();
}
JS;
$this->registerJs($js);
