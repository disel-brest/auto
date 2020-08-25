<?php

use app\modules\main\models\Ad;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\board\entities\AdMessage\AdDialog */

$this->title = "Диалог #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения м/у пользователями', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-dialog-view box box-primary">
    <div class="box-header">
        <?= Html::a('Объявление', Ad::getUrl($model->ad_type, $model->ad_id), ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getAdMessages()->with('user'),
            ]),
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'user.username',
                'message',
                'created_at:datetime',
                'is_new:boolean'
            ]
        ]) ?>
    </div>
</div>
