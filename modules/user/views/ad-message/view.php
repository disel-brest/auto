<?php
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $dialog \app\board\entities\AdMessage\AdDialog */
/* @var $model \app\board\forms\AdMessageCreateForm */

$itemRender = $dialog->ad->getItemViewPath();

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
            <a href="<?= \yii\helpers\Url::to(['/user/ad-message/index']) ?>">Вернуться к Моим Сообщениям</a>
        </div>
        <div class="message_wrap">
            <div class="message_item-advert">

                <?= $this->render($itemRender[0], [$itemRender[1] => $dialog->ad]) ?>

                <div class="advert_msg">
                    <div class="advert_msg-user">
                        <div class="advert_msg-img">
                            <img src="<?= $dialog->user->avatarUrl ?>" alt="">
                        </div>
                        <div class="advert_msg-info">
                            <div class="advert_msg-name"><?= $dialog->user->username ?></div>
                            <div class="advert_msg-city"><?= $dialog->user->city->name ?></div>
                        </div>
                    </div>
                    <div class="advert_msg-btns">
                        <div class="counter-msg"><?= \app\helpers\PluralForm::get(count($dialog->adMessages), "собщение", "сообщения", "сообщений") ?></div>
                        <button href="#complain-msg" class="complain-msg popup-msg complaint-link" data-ad-id="<?= $dialog->ad_id ?>" data-ad-type="<?= $dialog->ad_type ?>">Q</button>
                        <button class="delete-msg popup-link" data-dialog-id="<?= $dialog->id ?>">X</button>
                    </div>
                </div>
            </div>
            <div class="message_item-content">
                <div class="message_item-history">
                <?php foreach ($dialog->adMessages as $message) : ?>
                    <div class="message_item">
                        <div class="message_item-user">
                            <div class="message_item-name"><?= $message->user->username ?></div>
                            <div class="message_item-date"><time data-time-left="<?= $message->created_at ?>" data-ago="1"></time></div>
                        </div>
                        <div class="message_item-text">
                            <p><?= $message->message ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php $form = ActiveForm::begin() ?>
                <div class="message_add">
                    <p class="message_add-title">Написать сообщение</p>
                    <div class="message_add-text">
                        <?= $form->field($model, 'message')->textarea(['rows' => 10])->label(false) ?>
                    </div>
                    <button class="message_add-btn" type="submit">Отправить</button>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
