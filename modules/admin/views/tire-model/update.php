<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TireModel */

$this->title = 'Редактирование модели: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модели шин', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="tire-model-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
