<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $categoriesDataProvider yii\data\ActiveDataProvider */
/* @var $optionsDataProvider yii\data\ActiveDataProvider */
/* @var $categories \app\modules\main\models\CarOptions[] */
/* @var $categoryFilterId int */

$this->title = 'Опции авто';
$this->params['breadcrumbs'][] = $this->title;
$categoriesFilter = ArrayHelper::map($categories, 'id', 'name');
$categoriesFilter[0] = "Все категории";

?>
<div class="container-fluid">
    <div class="box box-primary no-padding">
        <div class="box-header with-border">
            <h3 class="box-title">Опции</h3>
            <div class="box-tools pull-right">
                <div class="has-feedback">
                    <?php ActiveForm::begin([
                        'id' => 'category-selector-form',
                        'method' => 'post'
                    ]) ?>
                        <?= Html::dropDownList('category_id', $categoryFilterId, $categoriesFilter, [
                            //'prompt' => 'Выберите категорию',
                            'class' => 'form-control',
                            'id' => 'category-selector'
                        ]) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $optionsDataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    [
                        'label' => 'Категория',
                        //'name' => 'categoryName',
                        'attribute' => 'categoryName',
                    ],
                    'name',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
        <div class="box-footer text-right">
            <?= Html::a('Добавить опцию', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            &nbsp;&nbsp;&nbsp;
            <?= Html::a('Добавить категорию', ['create-category'], ['class' => 'btn btn-primary btn-flat']) ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.addEventListener("load", function () {
        $("#category-selector").on("change", function () {
            $("#category-selector-form").submit();
        })
    });
</script>