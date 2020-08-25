<?php

namespace app\board\forms;


use app\board\entities\AdMessage\AdDialog;
use app\board\entities\Message\Dialog;
use yii\base\Model;

class AdMessageCreateForm extends Model
{
    public $message;
    public $subject;
    public $dialog;
    public $messageDialog;

    public function __construct(AdDialog $dialog = null, array $config = [])
    {
        $this->dialog = $dialog;
        if ($dialog) {
            $this->subject = $dialog->lastMessage->subject;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['message', 'required'],
            ['message', 'string'],
            ['message', 'dialogValidate'],
        ];
    }

    public function dialogValidate($attribute, $params, $validator)
    {
        if ($this->dialog && $this->dialog->user_id != \Yii::$app->user->id && $this->dialog->owner_id != \Yii::$app->user->id) {
            $this->addError($attribute, 'Вы пишете не в свой диалог');
        }
    }

    public function attributeLabels()
    {
        return [
            'message' => 'Сообщение'
        ];
    }
}