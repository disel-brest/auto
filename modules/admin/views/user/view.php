<?php

use app\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                'username',
                [
                    'attribute' => 'city.name',
                    'label' => 'Город'
                ],
                //'auth_key',
                //'email_confirm_token:email',
                //'password_hash',
                //'password_reset_token',
                'email:email',
                'phone',
                'phone_operator',
                'callTimeFrom',
                'callTimeTo',
                [
                    'label' => 'Аватар',
                    'value' => function(User $user) {
                        return '<img src="' . $user->avatarUrl . '" style="max-height: 100px;">';
                    },
                    'format' => 'raw',
                ],
                'statusLabel:raw',
                [
                    'label' => 'Кол-во объявлений',
                    'attribute' => 'adCount',
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
