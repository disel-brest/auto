<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\board\entities\AutoServiceWork */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Виды работ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-work-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'label' => 'Категория',
                    'attribute' => 'category.name',
                ],
                'name',
            ],
        ]) ?>
    </div>
</div>
