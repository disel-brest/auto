<?php

use app\helpers\AdHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\TireBrand;
use app\modules\main\models\TireModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TireSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary" id="castle-form-box">
    <div class="box-header with-border">
        <?= Html::a('Добавить объявление', ['create'], ['class' => 'btn btn-flat btn-success']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                //'user_id',
                [
                    'attribute' => 'brand_id',
                    'value' => 'brand.name',
                    'label' => 'Марка',
                    'filter' => ArrayHelper::map(TireBrand::find()->asArray()->all(), 'id', 'name')
                ],
                [
                    'attribute' => 'model_id',
                    'value' => 'model.name',
                    'label' => 'Модель',
                    'filter' => ArrayHelper::map($searchModel->brand_id ? TireModel::find()->where(['brand_id' => $searchModel->brand_id])->asArray()->all() : [], 'id', 'name')
                ],
                [
                    'attribute' => 'tire_type',
                    'value' => 'tireTypeName',
                    'filter' => \app\helpers\AutoHelper::getTireTypesArray()
                ],

                // 'is_new',
                // 'season',
                // 'radius',
                // 'width',
                // 'aspect_ratio',
                // 'amount',
                // 'photo:ntext',
                // 'bargain',
                'description:ntext',
                [
                    'attribute' => 'price',
                    'value' => 'priceNormal'
                ],
                // 'condition',
                'views',
                [
                    'attribute' => 'status',
                    'filter' => Ad::getStatusesArray(),
                    'value' => function (Ad $model) {
                        return AdHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                // 'active_till',
                'created_at:datetime',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
