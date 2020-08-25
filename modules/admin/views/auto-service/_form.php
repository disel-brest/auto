<?php

use app\board\entities\AutoServiceCategory;
use app\board\entities\AutoServiceWork;
use app\helpers\DateHelper;
use app\modules\user\models\User;
use kartik\widgets\FileInput;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoService\AutoServiceCreateForm */
/* @var $form yii\widgets\ActiveForm */

/*$this->registerCssFile("@web/css/sputnik_maps_full.css");
$this->registerJsFile("@web/js/sputnik_maps_full.js");
$js = <<<JS
var map = L.sm().map('map', {
		zoomControl: true,
		minZoom: 3,
		maxZoom: 19
	})
	.setView([43.591389, 39.757566], 10);
JS;
$this->registerJs($js);*/
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype'=>'multipart/form-data']
]); ?>

<div class="auto-service-form box box-primary">
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'subText')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?= $form->field($model, 'legalName')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'city')->widget(AutoComplete::className(), [
                    'clientOptions' => [
                        'source' => new JsExpression("function(request, response) {
                    $.getJSON('/main/default/get-cities', {
                        city: request.term
                    }, response);
                }")
                    ],
                    'options' => [
                        'placeholder' => 'Укажите город',
                        'class' => 'form-control',
                        'id' => 'city-input'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'street')->textInput(['maxlength' => true, 'id' => 'street-input']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'UNP')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'year')->widget(MaskedInput::className(), [
                    'mask' => '9999',
                ]) ?>
            </div>
        </div>

        <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'about')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'info')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'background')->widget(FileInput::class, [
            'options' => [
                'accept' => 'image/*',
                'multiple' => false,
            ]
        ]) ?>

    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border"><h3 class="box-title">График работы</h3></div>
    <div class="box-body">
        <?php if (isset($model->errors['workScheduleDay'])) {
            ?>
            <div class="form-group has-error">
                <div class="help-block"><?= $model->errors['workScheduleDay'][0] ?></div>
            </div>
            <?php
        } ?>
        <?php for ($i = 1; $i <= 7; $i++) : ?>
        <div class="row">
            <div class="col-xs-2"><?= $form->field($model, 'workScheduleDay[' . $i . ']', ['enableError' => false])->checkbox([
                    'label' => DateHelper::getDaysArray()[$i],
                ]) ?></div>
            <div class="col-xs-2"><?= $form->field($model, 'workScheduleFrom[' . $i . ']')->widget(MaskedInput::className(), [
                    'mask' => '99:99',
                ])->label(false) ?></div>
            <div class="col-xs-1 text-center">-</div>
            <div class="col-xs-2"><?= $form->field($model, 'workScheduleTill[' . $i . ']')->widget(MaskedInput::className(), [
                    'mask' => '99:99',
                ])->label(false) ?></div>
        </div>
        <?php endfor; ?>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border"><h3 class="box-title">Телефоны</h3></div>
    <div class="box-body phonesContainer">
        <?php if (isset($model->errors['phones'])) {
            ?>
            <div class="form-group has-error">
                <div class="help-block"><?= $model->errors['phones'][0] ?></div>
            </div>
            <?php
        } ?>
        <?php foreach ($model->phones as $k => $phone) : ?>
            <?php if ($phone) : ?>
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <?= Html::dropDownList('AutoServiceCreateForm[phoneOperators][]', $model->phoneOperators[$k], User::getPhoneOperatorsArray(), ['class' => 'form-control']) ?>
                        <div class="help-block"></div>
                    </div>            </div>
                <div class="col-xs-9">
                    <div class="form-group">
                        <input type="text" class="form-control" name="AutoServiceCreateForm[phones][]" value="<?= $phone ?>">
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="row">
            <div class="col-xs-3">
                <div class="form-group">
                    <?= Html::dropDownList('AutoServiceCreateForm[phoneOperators][]', null, User::getPhoneOperatorsArray(), ['class' => 'form-control']) ?>
                    <div class="help-block"></div>
                </div>            </div>
            <div class="col-xs-9">
                <div class="form-group">
                    <input type="text" class="form-control" name="AutoServiceCreateForm[phones][]">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer with-border">
        <button type="button" class="btn btn-sm btn-primary btn-flat" onclick="addPhone();">Добавить телефон</button>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border"><h3 class="box-title">Координаты</h3></div>
    <div class="box-body">
        <div id="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'coordinates')->textInput(['maxlength' => true])->label(false) ?>
            </div>
            <div class="col-sm-6">
                <button type="button" class="btn btn-sm btn-primary btn-flat" onclick="getCoords();">Открыть карту</button>
            </div>
        </div>
    </div>
</div>


<div class="box box-default">
    <div class="box-header with-border"><h3 class="box-title">Фотографии</h3></div>
    <div class="box-body">
        <?= $form->field($model->photos, 'files[]')->widget(FileInput::class, [
            'options' => [
                'accept' => 'image/*',
                'multiple' => true,
            ]
        ])->label(false)->hint("Для добавления нескольких изображений нужно при выборе файлов выделить сразу несколько нужных вам фотографий") ?>
    </div>
</div>

<?php /*
<div class="box box-primary" id="photos">
    <div class="box-header"><h3 class="box-title">Фотографии</h3></div>
    <div class="box-body">
        <div class="row"></div>
        <?= FileInput::widget([
            'name' => 'photo[]',
            'options' => [
                'accept' => 'image/*',
                'multiple' => true,
            ]
        ]) ?>
        <div class="form-group">
            <?= Html::button('Загрузить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
 */ ?>

<div class="box box-default">
    <div class="box-header with-border"><h3 class="box-title">Услуги</h3></div>
    <div class="box-body">
        <?php if (isset($model->errors['works'])) {
            ?>
            <div class="form-group has-error">
                <div class="help-block">Необходимо выбрать хотя бы одну услугу.</div>
            </div>
            <?php
        } ?>
        <?php foreach (AutoServiceCategory::find()->all() as $category) : ?>
            <?php /* @var $category AutoServiceCategory */ ?>
            <div class="row col-xs-12">
                <h3><?= $category->name ?></h3>
                <?php foreach ($category->getAutoServiceWorks()->each() as $work) : ?>
                    <?= Html::checkbox('AutoServiceCreateForm[works][]', in_array($work->id, $model->works ? $model->works : []), ['label' => $work->name, 'value' => $work->id]) ?>
                    <?php //= $form->field($model, 'works['.$work->id.']')->checkbox(['label' => $work->name, 'value' => $work->id])->label(false) ?>
                <?php endforeach; ?>
                <?php /*= $form->field($model, 'works')
                    ->checkboxList(ArrayHelper::map($category->getAutoServiceWorks()->asArray()->all(), 'id', 'name'))
                    ->label(false)*/ ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Добавить сервис', ['class' => 'btn btn-success btn-flat']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="hidden templates">
    <div class="row phoneRow">
        <div class="col-xs-3">
            <div class="form-group">
                <?= Html::dropDownList('AutoServiceCreateForm[phoneOperators][]', null, User::getPhoneOperatorsArray(), ['class' => 'form-control']) ?>
                <div class="help-block"></div>
            </div>            </div>
        <div class="col-xs-9">
            <div class="form-group">
                <input type="text" class="form-control" name="AutoServiceCreateForm[phones][]">
                <div class="help-block"></div>
            </div>
        </div>
    </div>
</div>
