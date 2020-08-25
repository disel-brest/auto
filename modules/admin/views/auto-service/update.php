<?php

use app\board\entities\AutoServiceCategory;
use app\board\helpers\AutoServiceHelper;
use app\helpers\DateHelper;
use app\modules\user\models\User;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \app\board\forms\manage\AutoService\AutoServiceEditForm */
/* @var $autoService \app\board\entities\AutoService */

$this->title = 'Редактирование автосервиса: ' . $autoService->name;
$this->params['breadcrumbs'][] = ['label' => 'Автосервисы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $autoService->name, 'url' => ['view', 'id' => $autoService->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="auto-service-update">

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
                ],
                'pluginOptions' => [
                    //'showPreview' => false,
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'initialPreview' => $autoService->background ? [
                        AutoServiceHelper::filesPath() . "/" . $autoService->background,
                    ] : [],
                    'initialPreviewAsData' => true,
                    /*'initialPreviewConfig' => [
                        ['caption' => 'Moon.jpg', 'size' => '873727'],
                        ['caption' => 'Earth.jpg', 'size' => '1287883'],
                    ],*/
                    'overwriteInitial' => true,
                ]
            ]) ?>

        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><h3 class="box-title">График работы</h3></div>
        <div class="box-body">
            <?php for ($i = 1; $i <= 7; $i++) : ?>
                <div class="row">
                    <div class="col-xs-2"><?= $form->field($model, 'workScheduleDay[' . $i . ']')->checkbox(['label' => DateHelper::getDaysArray()[$i]]) ?></div>
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
            <?php foreach ($model->phones as $i => $phone) : ?>
            <div class="row">
                <div class="col-xs-3">
                    <?= $form->field($model, 'phoneOperators['.$i.']')->dropDownList(User::getPhoneOperatorsArray())->label(false) ?>
                </div>
                <div class="col-xs-9">
                    <?= $form->field($model, 'phones['.$i.']')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
            <?php endforeach; ?>
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
        <div class="box-header with-border"><h3 class="box-title">Услуги</h3></div>
        <div class="box-body">
            <?php foreach (AutoServiceCategory::find()->all() as $category) : ?>
                <?php /* @var $category AutoServiceCategory */ ?>
                <div class="row col-xs-12">
                    <h3><?= $category->name ?></h3>
                    <?php foreach ($category->getAutoServiceWorks()->each() as $work) : ?>
                        <?= Html::checkbox($model->formName() . '[works][]', in_array($work->id, $model->works ? $model->works : []), ['label' => $work->name, 'value' => $work->id]) ?>
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
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="box box-primary" id="photos">
        <div class="box-header"><h3 class="box-title">Фотографии</h3></div>
        <div class="box-body">
            <div class="row">
                <?php foreach ($autoService->getPhotos() as $id => $photo): ?>
                    <div class="col-md-2 col-xs-3" style="text-align: center">
                        <div class="btn-group">
                            <?php /*= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo-up', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); */ ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $autoService->id, 'photo_id' => $id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                                'data-confirm' => 'Удалить фото?',
                            ]); ?>
                            <?php /*= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-photo-down', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); */ ?>
                        </div>
                        <div>
                            <?= Html::a(
                                Html::img(AutoServiceHelper::filesPath() . "/" .$photo, ['style' => 'max-height: 150px']),
                                AutoServiceHelper::filesPath() . "/" .$photo,
                                ['class' => 'thumbnail', 'target' => '_blank']
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php $form = ActiveForm::begin([
                'options' => ['enctype'=>'multipart/form-data'],
            ]); ?>

            <?= $form->field($photosForm, 'files[]')->label(false)->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true,
                ]
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

    <div class="hidden templates">
        <div class="row phoneRow">
            <div class="col-xs-3">
                <div class="form-group">
                    <?= Html::dropDownList('AutoServiceEditForm[phoneOperators][]', null, User::getPhoneOperatorsArray(), ['class' => 'form-control']) ?>
                    <div class="help-block"></div>
                </div>            </div>
            <div class="col-xs-9">
                <div class="form-group">
                    <input type="text" class="form-control" name="AutoServiceEditForm[phones][]">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>


</div>
