<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\board\entities\AdMessage\AdDialog */

$this->title = 'Update Ad Dialog: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ad Dialogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ad-dialog-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
