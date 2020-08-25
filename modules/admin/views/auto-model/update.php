<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AutoModel */

$this->title = 'Редактирование модели: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модели авто', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="auto-model-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
