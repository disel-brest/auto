<?php

use app\modules\main\models\Ad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Complaint */

$this->title = "Жалоба #" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Жалобы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complaint-view">
    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'typeName',
            'ad_id',
            [
                'label' => 'Объявление',
                'value' => function($model) {
                    return Html::a("Просмотреть", ['/admin/' . substr(Ad::getTypeUrlId($model->ad_type), 0, -1) . '/view', 'id' => $model->ad_id]);
                },
                'format' => 'html'
            ],
            [
                'label' => "От пользователя",
                'attribute' => 'user.username',
            ],
            'statusName',
            'message',
            'created_at:datetime',
            //'updated_at:datetime',
        ],
    ]) ?>

</div>
