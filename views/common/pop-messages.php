<?php
use yii\bootstrap\ActiveForm;

/* @var $ad \app\modules\main\models\AdCar|\app\modules\main\models\AdTire|\app\modules\main\models\AdWheel|\app\modules\main\models\AdPart */

?>

<!--Вслывающее окно Написать сообщение-->
<div class="popup_msg mfp-hide" id="open-msg">
	<div class="popup_msg-wrap">
        <?php $form = ActiveForm::begin([
            'id' => 'open-msg-form',
            'options' => [
                'data-ad-type' => $ad->type(),
                'data-ad-id' => $ad->id,
            ]
        ]) ?>
				<span class="popup-msg_title">
					Задать вопрос
				</span>
			<div class="popup-msg-text">
				<textarea name="msg-text" id="new-ad-message" required></textarea>
			</div>
			<button type="button" class="popup-msg_btn">Отправить</button>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="popup-success">
		Ваше сообщение отправлено
	</div>
</div>