<?php

use app\helpers\AdHelper;
use app\modules\admin\widgets\StatusSwitcherWidget;
use app\modules\main\models\AdPart;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AdPart */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Запчасти', 'url' => ['index']];
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
                [
                    'attribute' => 'fuelName',
                    'label' => 'Тип двигателя',
                ],
                [
                    'attribute' => 'engineVolume',
                    'label' => 'Объём двигателя',
                ],
                'year',
                [
                    'attribute' => 'bodyStyle',
                    'label' => 'Кузов',
                ],
                'categoryName',
                'name',
                'description',
                [
                    'attribute' => 'photo',
                    'value' => function(AdPart $model) {
                        return '<img src="'.$model->photoUrl.'" style="max-width:150px;height:max-height:150px;">';
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'priceNormal',
                    'label' => 'Цена',
                ],
                [
                    'attribute' => 'status',
                    'value' => function (AdPart $model) {
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
