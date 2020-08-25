<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/', 'password_reset_token' => $user->password_reset_token]);

?>
<div class="password-reset">
    <p><?= Html::encode($user->username) ?>, Вы запросили процедуру смены пароля на сайте <b><?= Yii::$app->name ?></b>.</p>

    <p>Перейдите по этой ссылке для того чтобы установить новый пароль:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>