<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\forms\AutoServiceWorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Виды работ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-work-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить вид работ', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Добавить категорию', ['create-category'], ['class' => 'btn btn-primary btn-flat']) ?>
        <div class="box-tools">
            <?= Html::a('Все категории', ['categories'], ['class' => 'btn btn-primary btn-flat']) ?>
        </div>
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
                [
                    'label' => 'Категория',
                    'value' => 'category.name',
                ],
                'name',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
