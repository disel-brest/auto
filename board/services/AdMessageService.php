<?php

namespace app\board\services;


use app\board\entities\AdMessage\AdDialog;
use app\board\entities\AdMessage\AdMessage;
use app\board\entities\Message\Dialog;
use app\board\entities\Message\Message;
use app\board\forms\AdMessageCreateForm;
use app\board\repositories\MessageRepository;
use app\modules\admin\forms\MailForm;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use app\modules\main\models\AdWheel;

class AdMessageService
{
    private $repository;
    private $transaction;

    public function __construct(MessageRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create($message, $adId, $adType, $userId)
    {
        if (!$dialog = $this->repository->findDialog($adId, $adType, $userId)) {
            $adClass = Ad::getAdClassByType($adType);
            /* @var $ad AdCar|AdWheel|AdPart|AdTire */
            if (!$ad = $adClass::findOne($adId)) {
                throw new \DomainException("Объявление не найдено");
            }
            $dialog = AdDialog::create($adId, $adType, $ad->user_id, $userId);
        }
        $this->transaction->wrap(function () use ($dialog, $message, $userId) {
            if ($dialog->isNewRecord) {
                $this->repository->saveDialog($dialog);
            }
            $subject = $dialog->ad->fullName;
            $message = AdMessage::create(
                $dialog->id,
                $userId,
                $subject,
                $message
            );
            $this->repository->save($message);
        });
    }

    public function send(AdMessageCreateForm $form)
    {
        $message = AdMessage::create($form->dialog->id, \Yii::$app->user->id, $form->subject, $form->message);
        $this->repository->save($message);
        return $message;
    }

    public function senMessage($subject, $message, $userFrom, $userTo)
    {
        $dialog = $this->repository->findMessageDialog($userFrom, $userTo);
        if (!$dialog) {
            $dialog = Dialog::create($userFrom, $userTo);
        }
        $this->transaction->wrap(function () use ($dialog, $subject, $message, $userFrom, $userTo) {
            if ($dialog->isNewRecord) {
                $this->repository->saveMessageDialog($dialog);
            }
            $messageEntity = Message::create($dialog->id, $userFrom, $subject, $message);
            $this->repository->saveMessage($messageEntity);
        });
    }

    public function setRead($dialogId, $isAdminMessage = false)
    {
        if ($isAdminMessage) {
            Message::updateAll(['is_new' => 0], [
                'and',
                ['dialog_id' => $dialogId],
                ['not', ['user_id' => \Yii::$app->user->id]]
            ]);
        } else {
            AdMessage::updateAll(['is_new' => 0], [
                'and',
                ['dialog_id' => $dialogId],
                ['not', ['user_id' => \Yii::$app->user->id]]
            ]);
        }
    }

    public function delete($id)
    {
        $this->repository->deleteDialog($id);
    }
}