<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoServiceWorkForm */

$this->title = 'Редактирование: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Виды работ', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="auto-service-work-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
