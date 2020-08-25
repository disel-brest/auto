<?php

use app\board\entities\AutoServiceCategory;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-work-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить категорию', ['create-category'], ['class' => 'btn btn-success btn-flat']) ?>
        <div class="box-tools">
            <?= Html::a('Виды работ', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => false,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'label' => 'Картинка',
                    'value' => function (AutoServiceCategory $model) {
                        return '<img style="height:100px;" src="' . $model->getPhotoUrl() . '">';
                    },
                    'format' => 'raw',
                ],
                'name',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}  {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-edit']), ['update-category', 'id' => $model->id]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), ['delete-category', 'id' => $model->id]);
                        }
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
