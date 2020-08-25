<?php

namespace app\modules\admin\forms;


use yii\base\Model;

class MailForm extends Model
{
    public $subject;
    public $message;
    public $users;

    public function rules()
    {
        return [
            [['subject', 'message', 'users'], 'required'],
            [['subject', 'message'], 'string'],
            ['users', 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => 'Заголовок сообщения',
            'message' => 'Сообщение',
        ];
    }
}