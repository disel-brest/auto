<?php

use app\modules\main\widgets\PartsTableWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$partsFilter = new \app\modules\main\models\filters\PartsFilter();

?>

<?= $this->render("../common/left-content"); ?>

<div class="content-right">
    <div class="breadcrumbs">
        <a href="">Главная</a> > <a href="javascript:void(0);">Автозапчасти б/у</a>
    </div>
    <div class="group-content">
        <div class="group-content-img">
            <a href="javascript:void(0);"></a>
        </div>
        <?= $this->render('../common/categories-menu') ?>
        <?= PartsTableWidget::widget() ?>
    </div>
</div>
