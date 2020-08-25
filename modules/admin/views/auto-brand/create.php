<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\AutoBrand */

$this->title = 'Новая марка';
$this->params['breadcrumbs'][] = ['label' => 'Марки авто', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
