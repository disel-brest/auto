<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жалобы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complaint-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'ad_id',
            'typeName',
            [
                'label' => "От пользователя",
                'attribute' => 'user.username',
            ],
            'statusName',
            'created_at:date',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>
</div>
