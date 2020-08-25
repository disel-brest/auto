<?php

use app\board\entities\AutoService;
use app\board\entities\AutoServicesWorksAssignment;
use app\board\entities\AutoServiceWork;
use app\board\helpers\AutoServiceHelper;
use app\helpers\DateHelper;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\board\entities\AutoService */
/* @var $photosForm \app\board\forms\manage\AutoService\PhotosForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Auto Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-service-view">
    <div class="box box-primary">
        <div class="box-header">
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-flat',
                'data' => [
                    'confirm' => 'Вы уверены?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="box-body table-responsive no-padding">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'label' => 'Название',
                        'value' => function (AutoService $autoService) {
                            return Html::a($autoService->name, ['/main/services/auto/auto-service/view', 'id' => $autoService->id], ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    'sub_text',
                    'legal_name',
                    [
                        'label' => 'Город',
                        'attribute' => 'city.name'
                    ],
                    'street',
                    'unp',
                    'year',
                    [
                        'label' => 'Телефоны',
                        'value' => function (AutoService $autoService) {
                            $html = '<ul>';
                            foreach (Json::decode($autoService->phones) as $phone) {
                                $html .= '<li>' . $phone[0] . ' (' . $phone[1] . ')</li>';
                            }
                            return $html . '</ul>';
                        },
                        'format' => 'raw'
                    ],
                    'site:url',
                    [
                        'label' => 'График работы',
                        'value' => function (AutoService $autoService) {
                            $html = '<ul>';
                            foreach (Json::decode($autoService->work_schedule) as $day => $time) {
                                $html .= '<li>' . DateHelper::getDaysArray()[$day] . ' : ' . $time[0] . ' - ' . $time[1] . '</li>';
                            }
                            return $html . '</ul>';
                        },
                        'format' => 'raw'
                    ],
                    'about:ntext',
                    'info',
                    [
                        'label' => 'Фон',
                        'value' => function (AutoService $autoService) {
                            return $autoService->background ? Html::img(AutoServiceHelper::filesPath() . "/" .$autoService->background, ['style' => 'max-height: 150px']) : "нет";
                        },
                        'format' => 'raw'
                    ],
                    //'photos:ntext',
                    'lat',
                    'lng',
                    [
                        'label' => 'Статус',
                        'value' => function (AutoService $autoService) {
                            return AutoServiceHelper::statusName($autoService->status);
                        }
                    ],
                    'views',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header"><h3 class="box-title">Виды работ</h3></div>
        <div class="box-body">
            <ul>
            <?php foreach ($model->works as $work) : ?>
                <li><?= $work->category->name ?> : <?= $work->name ?></li>
            <?php endforeach; ?>
            </ul>

            <?php /*foreach ($model->getWorkCategories()->all() as $category) : ?>
                <h4><?= $category->name ?></h4>
                <ul>
                <?php foreach ($model->getWorks()->where([AutoServiceWork::tableName() . '.category_id' => $category->id])->all() as $work) : ?>
                     <li><?= $work->name ?></li>
                <?php endforeach; ?>
                </ul>
            <?php endforeach;*/ ?>
        </div>
    </div>

    <div class="box box-primary" id="positions">
        <div class="box-header"><h3 class="box-title">Позиции</h3></div>
        <div class="box-body"><?= AutoServiceHelper::positionButtons($model) ?></div>
    </div>

    <div class="box box-primary" id="photos">
        <div class="box-header"><h3 class="box-title">Фотографии</h3></div>
        <div class="box-body">
            <div class="row">
                <?php foreach ($model->getPhotos() as $id => $photo): ?>
                    <div class="col-md-2 col-xs-3" style="text-align: center">
                        <div class="btn-group">
                            <?php /*= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo-up', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); */ ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $model->id, 'photo_id' => $id], [
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
</div>

