<?php

use app\helpers\AdHelper;
use app\modules\admin\widgets\StatusSwitcherWidget;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdTire;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AdTire */

$this->title = "Объявление #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Шины', 'url' => ['index']];
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
                    'label' => 'Марка',
                ],
                [
                    'attribute' => 'model.name',
                    'label' => 'Модель',
                ],
                'tireTypeName',
                'is_new:boolean',
                'seasonName',
                'radiusName',
                'width',
                'aspect_ratio',
                'amountName',
                [
                    'attribute' => 'photo',
                    'value' => function(AdTire $model) {
                        $images = [];
                        foreach ($model->getPhotos() as $photo) {
                            $images[] = '<img src="'.$model->getFilesPath(true).'/' . $photo . '" style="max-width:150px;max-height:150px;">';
                        }
                        return implode('&nbsp;', $images);
                    },
                    'format' => 'raw',
                ],
                'priceNormal',
                'bargain:boolean',
                'description:ntext',
                'condition',
                [
                    'attribute' => 'status',
                    'value' => function (AdTire $model) {
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
