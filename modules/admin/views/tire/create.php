<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $tireForm \app\modules\main\forms\AddTireForm */

$this->title = 'Новое объявление';
$this->params['breadcrumbs'][] = ['label' => 'Шины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-tire-create">

    <?= $this->render('_form', [
        'userForm' => $userForm,
        'tireForm' => $tireForm,
    ]) ?>

</div>
