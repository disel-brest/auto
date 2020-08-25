<?php

/* @var $this \yii\web\View */
/* @var $autoService \app\board\entities\AutoService */

use app\board\entities\AutoServiceWork;
use app\board\helpers\AutoServiceHelper;
use app\helpers\DateHelper;
use yii\bootstrap\Html;
use yii\helpers\Url;


echo Html::cssFile('/css/sputnik_maps_full.css');
$this->registerJsFile("@web/js/sputnik_maps_full.js");

$js = <<<JS
function setMap() {
    var sm = L.sm(),
	    map = sm.map('service-map', {
		    zoomControl: true,
		    minZoom: 3,
		    maxZoom: 19,
		    themePath: '/dist/themes/sputnik_maps/'
	    })
	.setView([{$autoService->lat}, {$autoService->lng}], 15);

    sm.addMarker(map, [{$autoService->lat}, {$autoService->lng}], {markerType: 'alt3'});
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
        setTimeout(setMap, 55);
        isShown = true;
    }
})
JS;
$this->registerJs($js);

?>
<div
    class="company-header"
    <?= $autoService->background && is_file(AutoServiceHelper::filesPath(false) . "/" . $autoService->background) ? 'style="background-image:url('.AutoServiceHelper::filesPath() . "/" . $autoService->background.')"' : '' ?>
>
    <div class="company-header-top">
        <div class="company-name"><?= $autoService->name ?></div>
        <div class="company-phone">
        <?php foreach ($autoService->getPhones() as $phone) : ?>
            <a href="tel:<?= $phone[0] ?>"><?= $phone[0] . " " . $phone[1] ?></a><br>
        <?php endforeach; ?>
        </div>
    </div>
    <div class="company-service-title">
        <h1><?= $autoService->sub_text ?></h1>
    </div>
    <div class="company-nav">
        <div class="tabs">
            <div class="tab active">
                <div class="tab_title">О компании</div>
            </div>
            <div class="tab">
                <div class="tab_title">Наши услуги</div>
            </div>
            <div class="tab">
                <div class="tab_title">Контакты</div>
            </div>
        </div>
    </div>
</div>
<div class="company-content">
    <div class="tab_content">
        <div class="tab_item">
            <div class="company-about">
                <h2>О компании</h2>
                <div class="company-about-text">
                    <?= $autoService->about ?>
                </div>
                <div class="company-gallery clearfix">
                <?php foreach ($autoService->getPhotos() as $photo) : ?>
                    <a class="company-gallery-item" data-fancybox-group="company-gallery" href="<?= AutoServiceHelper::filesPath() . "/" . $photo ?>">
                        <img src="<?= AutoServiceHelper::filesPath() . "/" . $photo ?>" alt="">
                    </a>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="tab_item">
            <div class="company-list-wrap">
                <div class="company-list-services">
                <?php foreach ($autoService->getWorkCategories()->all() as $category) : ?>
                    <div class="company-list-service">
                        <div class="company-list-item-title">
                            <?= $category->name ?>
                        </div>
                        <div class="company-list-item">
                            <ul>
                            <?php foreach ($autoService->getWorks()->where([AutoServiceWork::tableName() . '.category_id' => $category->id])->all() as $work) : ?>
                                <li><?= $work->name ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="company-gallery clearfix">
                <?php foreach ($autoService->getPhotos() as $photo) : ?>
                    <a class="company-gallery-item" data-fancybox-group="company-gallery" href="<?= AutoServiceHelper::filesPath() . "/" . $photo ?>">
                        <img src="<?= AutoServiceHelper::filesPath() . "/" . $photo ?>" alt="">
                    </a>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="tab_item" id="tab-map">
            <div class="company-contacts">
                <div class="company-contact-content">
                    <div class="company-title"><?= $autoService->legal_name ?></div>
                    <div class="company-address">г.<?= $autoService->city->name ?>, <?= $autoService->street ?></div>
                    <div class="company-phones">
                    <?php foreach ($autoService->getPhones() as $phone) : ?>
                        <a href="tel:<?= $phone[0] ?>"><?= $phone[0] ?><span class="operator"><?= $phone[1] ?></span></a>
                    <?php endforeach; ?>
                    </div>

                    <?php if ($autoService->unp || $autoService->site) : ?>
                    <div class="company-info-row">
                        <?= $autoService->unp ? "УНП " . $autoService->unp . "<br>" : "" ?>
                        <?= $autoService->site ? Html::a($autoService->site, !Url::isRelative($autoService->site) ? $autoService->site : "http://" . $autoService->site, ['target' => '_blank']) : "" ?>
                    </div>
                    <?php endif; ?>

                    <div class="company-schedule">
                        <?php $workSchedule = $autoService->getWorkSchedule() ?>
                        <p>Время работы</p>
                        <ul>
                        <?php for ($i = 1; $i <= 7; $i++) : ?>
                            <li>
                                <span class="schedule-day"><?= DateHelper::getDay($i) ?></span>
                                <span class="schedule-time"><?= isset($workSchedule[$i]) ? $workSchedule[$i][0] . " - " . $workSchedule[$i][1] : "выходной" ?></span>
                            </li>
                        <?php endfor; ?>
                        </ul>
                    </div>
                </div>
                <div class="company-contacts-map">
                    <div id="service-map" style="width: 725px;height: 523px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
