<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $carForm \app\modules\main\forms\AddCarForm */

$this->title = 'Новое объявление';
$this->params['breadcrumbs'][] = ['label' => 'Автомобили', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-car-create">

    <?= $this->render('_form', [
        'userForm' => $userForm,
        'carForm' => $carForm,
    ]) ?>

</div>
