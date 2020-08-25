<?php

use app\board\entities\AutoServiceCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoServiceWorkForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auto-service-work-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'category')->dropDownList(ArrayHelper::map(AutoServiceCategory::find()->asArray()->all(), 'id', 'name')) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
