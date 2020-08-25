<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TireBrand */

$this->title = 'Редактирование производителя шин: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Производители шин', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="tire-brand-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
