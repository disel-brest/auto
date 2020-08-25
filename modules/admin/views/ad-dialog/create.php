<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\board\entities\AdMessage\AdDialog */

$this->title = 'Create Ad Dialog';
$this->params['breadcrumbs'][] = ['label' => 'Ad Dialogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-dialog-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
