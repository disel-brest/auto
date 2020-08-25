<?php

use app\modules\main\components\Counter;

$this->title = "Админ-панель";

?><div class="container-fluid main-admin-page">
    <div class="row">
        <a href="<?= \yii\helpers\Url::to(['/admin/part/index']) ?>">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-wrench"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Запчасти</span>
                        <span class="info-box-number"><?= Yii::$container->get(Counter::class)->get(Counter::PARTS_ALL) ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </a>
        <a href="<?= \yii\helpers\Url::to(['/admin/car/index']) ?>">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-car"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Автомобили</span>
                        <span class="info-box-number"><?= Yii::$container->get(Counter::class)->get(Counter::CARS_ALL) ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </a>
        <a href="<?= \yii\helpers\Url::to(['/admin/tire/index']) ?>">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-dot-circle-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Шины</span>
                        <span class="info-box-number"><?= Yii::$container->get(Counter::class)->get(Counter::TIRES_ALL) ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </a>
        <a href="<?= \yii\helpers\Url::to(['/admin/wheel/index']) ?>">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-circle-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Диски</span>
                        <span class="info-box-number"><?= Yii::$container->get(Counter::class)->get(Counter::WHEELS_ALL) ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </a>
    </div>
</div>
<style type="text/css">
    .main-admin-page .info-box-content{
        color: #333;
    }
    .main-admin-page .info-box:hover, .main-admin-page .info-box:focus{
        background-color: #a2abdb;
    }
</style>
