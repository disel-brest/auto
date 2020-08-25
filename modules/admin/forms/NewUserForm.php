<?php

namespace app\modules\admin\forms;


use app\modules\main\models\City;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class NewUserForm extends Model
{
    public $username;
    public $city;
    public $email;
    public $phone;
    public $phone_operator;
    public $avatar;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 25],

            ['city', 'required'],
            ['city', 'string'],
            ['city', 'cityValidate'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email', 'message' => 'Непохоже на e-mail'],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 5, 'max' => 32],

            ['phone_operator', 'required'],
            ['phone_operator', 'in', 'range' => array_keys(User::getPhoneOperatorsArray())],

            ['avatar', 'file', 'extensions' => 'jpg,jpeg,png', 'maxSize' => 1024*512 * 1],
        ];
    }

    public function create()
    {
        $this->avatar = UploadedFile::getInstance($this, 'avatar');
        if ($this->validate()) {
            $user = new User([
                'username' => $this->username,
                'city_id' => $this->city ? City::findOne(['name' => $this->city])->id : null,
                'email' => $this->email,
                'phone' => $this->phone,
                'phone_operator' => $this->phone_operator,
            ]);

            $user->setPassword(Yii::$app->security->generateRandomString(16));
            $user->status = User::STATUS_USER;

            if ($user->save()) {
                if ($this->avatar instanceof UploadedFile) {
                    $avatarName = User::genAvatarName($this->avatar->extension);
                    $savedPath = $user->storagePath . '/' . $avatarName;
                    if (!$this->avatar->saveAs($savedPath)) {
                        $user->delete();
                        $this->addError('avatar', 'Ошибка сохранения аватарки на сервере');
                        return false;
                    }
                }

                return $user;
            }
        }

        return false;
    }

    public function cityValidate($attribute, $params, $validator)
    {
        if (($city = City::findOne(['name' => $this->city])) === null) {
            $this->addError('city', 'Такого города нет в базе');
        }
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'city' => 'Город',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'phone_operator' => 'Оператор',
            'avatar' => 'Аватарка',
        ];
    }
}