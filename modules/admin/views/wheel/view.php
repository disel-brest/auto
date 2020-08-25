<?php

use app\helpers\AdHelper;
use app\modules\admin\widgets\StatusSwitcherWidget;
use app\modules\main\models\AdWheel;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AdWheel */

$this->title = "Объявление #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Диски', 'url' => ['index']];
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
                'autoTypeName',
                'wheelTypeName',
                'is_new:boolean',
                [
                    'attribute' => 'autoBrand.name',
                    'label' => 'Марка авто',
                ],
                'firm',
                'radius',
                'bolts',
                'amountName',
                [
                    'attribute' => 'photo',
                    'value' => function(AdWheel $model) {
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
                    'value' => function (AdWheel $model) {
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
