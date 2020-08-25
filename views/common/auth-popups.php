<?php

/* @var $this \yii\web\View */

use app\modules\user\forms\LoginForm;
use app\modules\user\forms\PasswordResetRequestForm;
use app\modules\user\forms\ResetPasswordForm;
use app\modules\user\forms\SignupForm;
use yii\base\InvalidParamException;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\web\BadRequestHttpException;

$signupModel = new SignupForm();
$loginModel = new LoginForm();
$forgotPasswordResetRequestModel = new PasswordResetRequestForm();

if ($token = Yii::$app->request->get('password_reset_token')) {
    try {
        $forgotPasswordResetModel = new ResetPasswordForm($token);
        $js = <<<JS
window.addEventListener('load', function() {
    $.magnificPopup.open({
		items: {
			src: '#password-rec-pop-up',
			type: 'inline',
			midClick: true
		}
	});
});
JS;
        $this->registerJs($js);
    } catch (InvalidParamException $e) {
        throw new BadRequestHttpException($e->getMessage());
    }
}

?>

<div id="sign-in-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Вход</p>
    <?php $loginForm = ActiveForm::begin(['action' => '/login', 'id' => 'sign-in']); ?>
        <?= $loginForm->field($loginModel, 'email')->input('email', [
            'placeholder' => 'Электронная почта'
        ])->label(false); ?>
        <?= $loginForm->field($loginModel, 'password')->input('password', [
            'placeholder' => 'Пароль'
        ])->label(false); ?>
        <div class="check_block">
            <label for="form-checkbox">
                <input type="checkbox" id="form_checkbox" name="LoginForm[isAlien]">
                Чужой компьютер</label>
        </div>
        <button class="sign-in-button btn-confirm">Войти</button>
        <a href="#reg-in-pop-up" class="pop-up-register popup-link">Регистрация</a>
        <a class="forget-pass" href="#password-forg-pop-up">Забыли пароль?</a>
    <?php ActiveForm::end(); ?>
    <div class="popup-wrap-close"></div>
</div>
<div id="reg-in-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Регистрация</p>
    <p class="popup-description">
        после которой Вы сможете бесплатно размещать свои объявления на сайте
    </p>
    <?php $signupForm = ActiveForm::begin(['action' => '/signup', 'id' => 'reg-in']); ?>
        <?= $signupForm->field($signupModel, 'username')->input('text', [
            'placeholder' => 'Ваше имя',
            'class' => 'auth-form-input'
        ])->label(false); ?>
        <?= $signupForm->field($signupModel, 'email')->input('email', [
            'placeholder' => 'E-mail'
        ])->label(false); ?>
        <?= $signupForm->field($signupModel, 'password')->input('password', [
            'placeholder' => 'Придумайте пароль'
        ])->label(false); ?>
        <?= $signupForm->field($signupModel, 'passwordRepeat')->input('password', [
            'placeholder' => 'Введите пароль еще раз'
        ])->label(false); ?>

        <?= $signupForm->field($signupModel, 'verifyCode')->label(false)->widget(Captcha::className(), [
            'captchaAction' => '/user/default/captcha',
            'options' => [
                'placeholder' => 'Текст с картинки',
            ],
            'template' => '<div class="reg-in-captcha"><div class="reg-in-captcha-numbers">{image}</div><div class="reg-in-captcha-input">{input}<label for="reg-in-captcha-numbers">Обновить картинку</label></div></div>',
        ]) ?>
        <button type="submit" class="reg-in-button btn-confirm" href="#reg-msg-pop-up">Зарегистрироваться</button>
        <a href="#sign-in-pop-up" class="sign-in-link popup-link">Вход</a>
        <a class="forget-pass" href="#password-forg-pop-up">Забыли пароль?</a>
    <?php ActiveForm::end(); ?>
    <div class="popup-wrap-close"></div>
</div>
<div id="reg-msg-pop-up" class="mfp-hide popup-wrap popup-msg">
    <p class="popup-title">Регистрация завершена!</p>
    <p>Для подтверждения регистрации вам на электронную почту было направлено письмо</p>
    <div class="popup-wrap-close"></div>    
</div>
<div id="password-forg-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Забыли пароль?</p>
    <p>введите адрес Вашей электронной почты, на которую будут высланы инструкции по восстановлению пароля</p>
    <?php $forgotPasswordResetRequestForm = ActiveForm::begin(['action' => '/signup', 'id' => 'password-forg', 'method' => 'post']); ?>
        <?= $forgotPasswordResetRequestForm->field($forgotPasswordResetRequestModel, 'email')->input('email', [
            'placeholder' => 'Укажите Ваш e-mail'
        ])->label(false); ?>
        <input class="btn-confirm" type="submit"  value="Отправить запрос" id="password-forg-btn" href="#password-forg-msg-pop-up">
    <?php ActiveForm::end(); ?>
    <div class="popup-wrap-close"></div>    
</div>
<div id="password-forg-msg-pop-up" class="mfp-hide popup-wrap popup-msg">
    <p>На Вашу почту высланы инструкции по восстановлению пароля</p>
    <a href="#password-rec-pop-up" class="btn-confirm">Ок</a>
</div>

<?php
if (isset($forgotPasswordResetModel) && isset($token)) : ?>
<div id="password-rec-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Восстановление пароля</p>
    <?php $forgotPasswordResetForm = ActiveForm::begin(['action' => '/password-reset?token='.$token, 'id' => 'password-rec', 'method' => 'post']); ?>
        <div class="password-rec-email">
            тут заполненный e-mail
        </div>
        <label>
            новый пароль
        </label>
        <?= $forgotPasswordResetForm->field($forgotPasswordResetModel, 'password')->input('password')->label(false); ?>
        <label>
            повторите новый пароль
        </label>
        <?= $forgotPasswordResetForm->field($forgotPasswordResetModel, 'passwordRepeat')->input('password')->label(false); ?>
        <input class="btn-confirm" type="submit" value="Подтвердить">
    <?php ActiveForm::end(); ?>
</div>
<?php endif; ?>