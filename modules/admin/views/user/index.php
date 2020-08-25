<?php

use app\modules\user\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $mailModel \app\modules\admin\forms\MailForm */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">
    <!--<div class="box-header with-border">
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>-->
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' => 'users-grid',
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn',],
                'username',
                [
                    'attribute' => 'city_id',
                    'label' => 'Город',
                    'value' => 'city.name'
                ],
                //'auth_key',
                //'email_confirm_token:email',
                // 'password_hash',
                // 'password_reset_token',
                'email:email',
                'phone',
                // 'phone_operator',
                // 'call_time',
                // 'avatar',
                [
                    'attribute' => 'status',
                    'value' => 'statusLabel',
                    'format' => 'raw',
                    'filter' => User::getStatusesArray()
                ],
                'created_at:datetime',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'id' => 'send-mail-form',
    'options' => [
        'data-form-name' => $mailModel->formName(),
    ]
]) ?>
<div class="box box-default">
    <div class="box-body">
        <?= $form->field($mailModel, 'subject')->textInput() ?>
        <?= $form->field($mailModel, 'message')->textarea(['rows' => 10]) ?>
    </div>
    <div class="box-footer">
        <?= Html::button("Отправить", ['class' => 'btn btn-primary btn-flat', 'id' => 'send-mail-btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
