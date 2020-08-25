<?php

use app\board\entities\AutoService;
use app\board\helpers\AutoServiceHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\forms\AutoServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Автосервисы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить сервис', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
                //['class' => 'app\modules\admin\grid\AutoServicePlaceColumn'],
                'name',
                //'sub_text',
                'legal_name',
                [
                    'label' => 'Город',
                    'attribute' => 'city.name'
                ],
                'street',
                [
                    'label' => 'Статус',
                    'attribute' => 'status',
                    'value' => function (AutoService $autoService) {
                        return AutoServiceHelper::statusName($autoService->status);
                    },
                    'filter' => AutoServiceHelper::getStatusesArray(),
                ],
                'views',
                // 'unp',
                // 'year',
                // 'phones:ntext',
                // 'site',
                // 'work_schedule:ntext',
                // 'about:ntext',
                // 'info',
                // 'background',
                // 'photos:ntext',
                // 'lat',
                // 'lng',
                // 'sort',
                'created_at:datetime',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
