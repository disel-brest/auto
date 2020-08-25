<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\WheelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-wheel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'wheel_auto') ?>

    <?= $form->field($model, 'is_new') ?>

    <?= $form->field($model, 'wheel_type') ?>

    <?php // echo $form->field($model, 'auto_brand_id') ?>

    <?php // echo $form->field($model, 'firm') ?>

    <?php // echo $form->field($model, 'radius') ?>

    <?php // echo $form->field($model, 'bolts') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'bargain') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'condition') ?>

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
