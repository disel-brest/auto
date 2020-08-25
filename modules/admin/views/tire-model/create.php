<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TireModel */

$this->title = 'Новая модель';
$this->params['breadcrumbs'][] = ['label' => 'Модели шин', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tire-model-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
