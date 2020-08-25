<?php

/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $wheelForm \app\modules\main\forms\AddWheelForm */

$this->title = 'Новое объявление';
$this->params['breadcrumbs'][] = ['label' => 'Диски', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-wheel-create">

    <?= $this->render('_form', [
        'userForm' => $userForm,
        'wheelForm' => $wheelForm,
    ]) ?>

</div>
