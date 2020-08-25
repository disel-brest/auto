<?php

use app\modules\main\models\CarOptions;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\CarOptions */
/* @var $form yii\widgets\ActiveForm */
/* @var $isCategory boolean */

$isCategory = isset($isCategory) ? $isCategory : false;

?>

<div class="car-options-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= !$isCategory ? $form->field($model, 'parent_id')
        ->label('Категория')
        ->dropDownList(ArrayHelper::map(CarOptions::find()->where(['parent_id' => 0])->asArray()->all(), 'id', 'name')) : "" ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
