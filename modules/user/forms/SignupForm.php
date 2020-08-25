<?php

namespace app\modules\user\forms;

use app\modules\user\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $passwordRepeat;
    public $verifyCode;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 25],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Необходимо указать e-mail'],
            ['email', 'email', 'message' => 'Непохоже на e-mail'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Данный e-mail уже зарегистрирован'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'required', 'message' => 'Необходимо повторить пароль'],
            ['passwordRepeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают"],

            ['verifyCode', 'captcha','captchaAction'=>'/user/default/captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = strtolower($this->email);
            $user->setPassword($this->password);
            //$user->generateAuthKey();
            $user->generateEmailConfirmToken();
            $user->status = User::STATUS_WAITING;

            if ($user->save()) {
                Yii::$app->mailer->compose(['html' => '@app/mail/signup-activation'], ['user' => $user, 'password' => $this->password])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Подтверждение регистрации на ' . Yii::$app->name)
                    ->send();
                return $user;
            } else {
                print_r($user->getErrors());exit;
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'Ваш e-mail',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повторите пароль',
        ];
    }
}