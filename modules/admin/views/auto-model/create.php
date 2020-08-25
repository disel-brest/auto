<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AutoModel */

$this->title = 'Новая модель';
$this->params['breadcrumbs'][] = ['label' => 'Модели авто', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-model-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
