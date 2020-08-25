<?php

use app\helpers\AdHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdPart;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\PartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запчасти';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-primary" id="castle-form-box">
    <div class="box-header with-border">
        <?= Html::a('Добавить запчасти', ['create'], ['class' => 'btn btn-flat btn-success']) ?>
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
                    'label' => 'Марка авто',
                    'filter' => ArrayHelper::map(AutoBrand::find()->asArray()->all(), 'id', 'name')
                ],
                [
                    'attribute' => 'model_id',
                    'value' => 'model.name',
                    'label' => 'Модель авто',
                    'filter' => ArrayHelper::map($searchModel->brand_id ? AutoModel::find()->where(['brand_id' => $searchModel->brand_id])->asArray()->all() : [], 'id', 'name')
                ],
                // 'fuel_id',
                // 'engine_volume',
                // 'year',
                // 'body_style',
                [
                    'attribute' => 'categoryName',
                    'filter' => AdHelper::getCategoriesArray()
                ],
                'name',
                // 'description',
                // 'photo',
                // 'price',
                [
                    'attribute' => 'status',
                    'filter' => Ad::getStatusesArray(),
                    'value' => function (AdPart $model) {
                        return AdHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                // 'views',
                // 'active_till',
                'created_at:datetime',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
