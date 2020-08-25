<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoServiceWorkForm */

$this->title = 'Новый вид работ';
$this->params['breadcrumbs'][] = ['label' => 'Виды работ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-work-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
