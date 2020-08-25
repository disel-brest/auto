<?php

use app\helpers\AdHelper;
use app\modules\admin\widgets\StatusSwitcherWidget;
use app\modules\main\models\AdCar;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AdCar */

$this->title = "Объявление #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Автомобили', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary" id="castle-form-box">
    <div class="box-header with-border">
        <?= StatusSwitcherWidget::widget(['ad' => $model]) ?>
        <div class="box-tools">
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-flat',
                'data' => [
                    'confirm' => 'Вы уверены?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'user.username',
                [
                    'attribute' => 'brand.name',
                    'label' => 'Марка авто',
                ],
                [
                    'attribute' => 'model.name',
                    'label' => 'Модель авто',
                ],
                'year',
                'odometer',
                [
                    'attribute' => 'bodyStyle',
                    'label' => 'Кузов',
                ],
                [
                    'attribute' => 'fuelName',
                    'label' => 'Тип двигателя',
                ],
                [
                    'attribute' => 'engineVolume',
                    'label' => 'Объём двигателя',
                ],
                [
                    'attribute' => 'transmissionName',
                    'label' => 'Коробка передач',
                ],
                [
                    'attribute' => 'drivetrainName',
                    'label' => 'Привод',
                ],
                [
                    'attribute' => 'colorName',
                    'label' => 'Цвет',
                ],
                [
                    'attribute' => 'photo',
                    'value' => function(AdCar $model) {
                        return '<img src="'.$model->mainPhoto.'" style="max-width:150px;height:max-height:150px;">';
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'priceNormal',
                    'label' => 'Цена',
                ],
                [
                    'label' => 'Опции',
                    'value' => function($model) {
                        $html = '';
                        foreach ($model->options as $option) {
                            /* @var $option \app\modules\main\models\CarOptions */
                            $html .= '<span class="label label-default">' . $option->name . '</span>&nbsp;';
                        }
                        return $html;
                    },
                    'format' => 'raw',
                ],
                'bargain:boolean',
                'change:boolean',
                'law_firm:boolean',
                'description:ntext',
                [
                    'attribute' => 'status',
                    'value' => function (AdCar $model) {
                        return AdHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                'views',
                'active_till:datetime',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
