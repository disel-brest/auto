<?php

namespace app\board\jobs;


use app\modules\user\models\User;
use Yii;
use yii\base\Object;
use yii\queue\Job;
use yii\queue\Queue;

class MailJob extends Object implements Job
{
    public $subject;
    public $message;
    public $userId;

    public function execute($queue)
    {
        if ($user = $this->findUser($this->userId)) {
            Yii::$app->mailer->compose('mail-message', ['subject' => $this->subject, 'message' => $this->message])
                ->setSubject("Сообщение от администратора сайте " . Yii::$app->params['name'])
                ->setFrom([Yii::$app->params['adminEmail'] => 'Admin'])
                ->setTo($user->username ? [$user->email => $user->username] : $user->email)
                ->send();
        }
    }

    /**
     * @param $id
     * @return User
     */
    private function findUser($id)
    {
        return User::findOne($id);
    }
}