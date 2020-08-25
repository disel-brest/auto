<?php

use app\board\entities\AdMessage\AdDialog;
use app\modules\main\models\Ad;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\AdDialogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения между пользователями';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-dialog-index box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                [
                    'label' => 'Категория',
                    'attribute' => 'category',
                    'value' => function (AdDialog $dialog) {
                        return Ad::getTypeName($dialog->ad_type);
                    },
                    'filter' => Ad::getTypesArray()
                ],
                [
                    'label' => 'Дата',
                    'value' => function (AdDialog $dialog) {
                        return $dialog->lastMessage->created_at;
                    },
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_from',
                        'attribute2' => 'date_to',
                        'type' => DatePicker::TYPE_RANGE,
                        'separator' => '-',
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]),
                    'format' => 'datetime',
                ],

                ['class' => 'yii\grid\ActionColumn', 'template' => "{view}"],
            ],
        ]); ?>
    </div>
</div>
