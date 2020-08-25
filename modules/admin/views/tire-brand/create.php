<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TireBrand */

$this->title = 'Новый производитель';
$this->params['breadcrumbs'][] = ['label' => 'Производители шин', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tire-brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
