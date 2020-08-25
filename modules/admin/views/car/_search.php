<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\CarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-car-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'brand_id') ?>

    <?= $form->field($model, 'model_id') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'odometer') ?>

    <?php // echo $form->field($model, 'body_style') ?>

    <?php // echo $form->field($model, 'fuel_id') ?>

    <?php // echo $form->field($model, 'engine_volume') ?>

    <?php // echo $form->field($model, 'transmission') ?>

    <?php // echo $form->field($model, 'drivetrain') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'bargain') ?>

    <?php // echo $form->field($model, 'change') ?>

    <?php // echo $form->field($model, 'law_firm') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'views') ?>

    <?php // echo $form->field($model, 'active_till') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
