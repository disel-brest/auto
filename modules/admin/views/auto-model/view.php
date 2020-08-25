<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AutoModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модели авто', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-model-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Марка',
                'attribute' => 'brand.name',
            ],
            'name',
        ],
    ]) ?>

</div>
