<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\board\entities\AdMessage\AdDialog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-dialog-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive no-padding">

        <?= $form->field($model, 'ad_id')->textInput() ?>

        <?= $form->field($model, 'ad_type')->textInput() ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
