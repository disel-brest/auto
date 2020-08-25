<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\forms\AutoServiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auto-service-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'sub_text') ?>

    <?= $form->field($model, 'legal_name') ?>

    <?= $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'unp') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'phones') ?>

    <?php // echo $form->field($model, 'site') ?>

    <?php // echo $form->field($model, 'work_schedule') ?>

    <?php // echo $form->field($model, 'about') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'background') ?>

    <?php // echo $form->field($model, 'photos') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
