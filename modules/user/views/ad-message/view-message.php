<?php
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $dialog \app\board\entities\Message\Dialog */

?>
<div class="content-left">
    <?= $this->render('@app/modules/user/views/common/user-bar') ?>
</div>
<div class="content-right">
    <div class="breadcrumbs">
        <a href="#">Главная</a> > <a href="#">Личный кабинет</a> > <a href="#">Мои сообщения</a>
    </div>

    <?= $this->render('@app/modules/user/views/common/user-block', ['user' => Yii::$app->user->identity]) ?>

    <div id="my-messages">
        <div class="message_page-back">
            <a href="<?= Url::to(['/user/ad-message/index']) ?>">Вернуться к Моим Сообщениям</a>
        </div>
        <?php foreach ($dialog->messages as $message) : ?>
            <div class="message_wrap">
                <div class="message_admin">
                    <div class="message_admin-title"><?= $message->subject ?></div>
                    <div class="message_admin-text"><?= $message->message ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
