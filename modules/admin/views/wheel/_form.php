<?php

use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AutoBrand;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $userForm \app\modules\admin\forms\NewUserForm */
/* @var $wheelForm \app\modules\main\forms\AddWheelForm */

$form = ActiveForm::begin();

if (isset($wheelForm)) {
    echo $this->render("@app/modules/admin/views/common/new-user-form", ['userForm' => $userForm, 'form' => $form]);
}
?>

<div class="box box-primary">
    <div class="box-body table-responsive">
        <div class="container-fluid no-padding">
            <div class="row no-padding">
                <div class="col-md-6">

                    <?= $form->field($wheelForm, 'auto_type')->dropDownList(AutoHelper::getWheelAutoArray()) ?>

                    <?= $form->field($wheelForm, 'wheel_type')->dropDownList(AutoHelper::getWheelTypesArray()) ?>

                    <?= $form->field($wheelForm, 'auto_brand_id')
                        ->dropDownList(ArrayHelper::map(AutoBrand::find()->asArray()->all(), 'id', 'name')) ?>

                    <?= $form->field($wheelForm, 'firm')->textInput(['maxlength' => true]) ?>

                </div>
                <div class="col-md-6">

                    <?= $form->field($wheelForm, 'radius')->dropDownList(AutoHelper::getTireRadiusArray()) ?>

                    <?= $form->field($wheelForm, 'bolts')->dropDownList(AutoHelper::getWheelBoltsArray()) ?>

                    <?= $form->field($wheelForm, 'amount')->dropDownList(AutoHelper::getTireAmountArray()) ?>

                    <?= $form->field($wheelForm, 'condition')->dropDownList(AutoHelper::getTireConditionArray()) ?>

                </div>
                <div class="col-sm-6">

                    <?= $form->field($wheelForm, 'price')->input("number") ?>

                </div>
                <div class="col-sm-6">

                    <?= $form->field($wheelForm, 'is_new')->checkbox() ?>

                    <?= $form->field($wheelForm, 'bargain')->checkbox() ?>

                </div>
            </div>
            <div class="row no-padding">
                <div class="col-lg-12">
                    <?= $form->field($wheelForm, 'description')->textarea(['rows' => 6]) ?>
                </div>
            </div>
            <div class="row no-padding">
                <div class="add-block">
                    <div class="wrap">
                        <div class="add-photos">
                            <?php PhotoHelper::renderPhotosForm(Ad::TYPE_WHEEL, $wheelForm->saved_photos, $wheelForm) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton(isset($wheelForm) ? 'Добавить объявление' : 'Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
