<?php

use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\Ad;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\WheelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Диски';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary" id="castle-form-box">
    <div class="box-header with-border">
        <?= Html::a('Добавить объявление', ['create'], ['class' => 'btn btn-flat btn-success']) ?>
        <?php // $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                //'user_id',
                [
                    'attribute' => 'wheel_auto',
                    'value' => 'autoTypeName',
                    'filter' => AutoHelper::getWheelAutoArray()
                ],
                //'is_new',
                [
                    'attribute' => 'wheel_type',
                    'value' => 'wheelTypeName',
                    'filter' => AutoHelper::getWheelTypesArray()
                ],
                'description:ntext',
                // 'auto_brand_id',
                // 'firm',
                // 'radius',
                // 'bolts',
                // 'amount',
                // 'photo:ntext',
                [
                    'attribute' => 'price',
                    'value' => 'priceNormal',
                ],
                'views',
                // 'bargain',
                // 'condition',
                [
                    'attribute' => 'status',
                    'filter' => Ad::getStatusesArray(),
                    'value' => function (Ad $model) {
                        return AdHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                //'active_till:datetime',
                'created_at:datetime',
                //'updated_at:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
