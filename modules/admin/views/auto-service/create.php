<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoService\AutoServiceCreateForm */

$this->title = 'Новый автосервис';
$this->params['breadcrumbs'][] = ['label' => 'Автосервисы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

</div>
