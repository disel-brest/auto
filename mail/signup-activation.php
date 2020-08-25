<?php

/* @var $user \app\modules\user\models\User */
/* @var $password string */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/email-confirm', 'token' => $user->email_confirm_token]);
$homeUrl = Yii::$app->urlManager->createAbsoluteUrl('/');

?>
<h3 style="color:darkred;"></h3>
<?= $user->username ?>, Вы зарегистрировались на сайте <a href="<?= $homeUrl ?>"><?= Yii::$app->name ?></a><br /><br />
Для подтверждения регистрации Вам необходимо перейти по этой ссылке: <br /><br />
<p>
    <strong><a href="<?= $confirmLink ?>"><?= $confirmLink ?></a></strong>
</p>
<br />
<p>
    Если Вы не регистрировались на сайте то просто проигнорируйте это письмо
</p>