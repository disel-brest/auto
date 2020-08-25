<?php
use app\helpers\PluralForm;
use yii\widgets\LinkPager;

/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $messagesDataProvider \yii\data\ActiveDataProvider */
/* @var $messagesCount int */

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
        <div class="my-adverts-title">
            <span>Мои сообщения<span class="count-adds">(<?= $messagesCount ?>)</span></span>
        </div>
        <div class="messages-all">
            <div class="messages_table">
                <div class="messages_table-header">
                    <div class="messages_table-date">Дата</div>
                    <div class="messages_table-topic">Тема сообщения</div>
                    <div class="messages_table-sender">Отправитель</div>
                    <div class="messages_table-city">Город</div>
                    <div class="messages_table-history">Сообщение</div>
                </div>
                <div class="messages_table-body">
                <?php foreach ($messagesDataProvider->getModels() as $dialog) : ?>
                    <?php /* @var $dialog \app\board\entities\Message\Dialog */ ?>
                    <a
                            href="<?= \yii\helpers\Url::to(['/user/ad-message/view-message', 'id' => $dialog->id]) ?>"
                            class="messages_table-row<?= $dialog->lastMessage->is_new && $dialog->lastMessage->user_id != Yii::$app->user->id ? " new-message" : "" ?>"
                    >
                        <div class="messages_table-date"><time data-time-left="<?= $dialog->lastMessage->created_at ?>" data-ago="1"></time></div>
                        <div class="messages_table-topic"><?= $dialog->lastMessage->subject ?></div>
                        <div class="messages_table-sender"><?= $dialog->interlocutor->username ?></div>
                        <div class="messages_table-city"><?= $dialog->interlocutor->city->name ?></div>
                        <div class="messages_table-history"><?= $dialog->lastMessage->is_new && $dialog->lastMessage->user_id != Yii::$app->user->id ? "Новое сообщение" : PluralForm::get($dialog->messagesCount, "сообщение", "сообщения", "сообщений") ?></div>
                    </a>
                <?php endforeach; ?>
                <?php foreach ($dataProvider->getModels() as $dialog) : ?>
                    <?php /* @var $dialog \app\board\entities\AdMessage\AdDialog */ ?>
                    <a
                        href="<?= \yii\helpers\Url::to(['/user/ad-message/view', 'id' => $dialog->id]) ?>"
                        class="messages_table-row<?= $dialog->lastMessage->is_new && $dialog->lastMessage->user_id != Yii::$app->user->id ? " new-message" : "" ?>"
                    >
                        <div class="messages_table-date"><time data-time-left="<?= $dialog->lastMessage->created_at ?>" data-ago="1"></time></div>
                        <div class="messages_table-topic"><?= $dialog->lastMessage->subject ?></div>
                        <div class="messages_table-sender"><?= $dialog->user->username ?></div>
                        <div class="messages_table-city"><?= $dialog->user->city->name ?></div>
                        <div class="messages_table-history"><?= $dialog->lastMessage->is_new && $dialog->lastMessage->user_id != Yii::$app->user->id ? "Новое сообщение" : PluralForm::get($dialog->messagesCount, "сообщение", "сообщения", "сообщений") ?></div>
                    </a>
                <?php endforeach; ?>
                </div>
            </div>

            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination
            ]) ?>

        </div>
    </div>
</div>
