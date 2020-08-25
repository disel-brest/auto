<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AutoBrand */

$this->title = 'Редактирование марки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Марки авто', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="auto-brand-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
