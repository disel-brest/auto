<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoServiceCategoryForm */

$this->title = 'Новая категория работ';
$this->params['breadcrumbs'][] = ['label' => 'Виды работ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-work-create">

    <div class="auto-service-work-form box box-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body table-responsive">

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'photo')->fileInput(['accept' => 'image/*']) ?>

        </div>
        <div class="box-footer">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
