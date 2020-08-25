<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\CarOptions */

$this->title = 'Новая опция';
$this->params['breadcrumbs'][] = ['label' => 'Опции авто', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-options-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
