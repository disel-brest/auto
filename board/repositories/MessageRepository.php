<?php

namespace app\board\repositories;


use app\board\entities\AdMessage\AdDialog;
use app\board\entities\AdMessage\AdMessage;
use app\board\entities\Message\Dialog;
use app\board\entities\Message\Message;
use yii\data\ActiveDataProvider;

class MessageRepository
{
    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * @param $id
     * @return \yii\db\ActiveRecord|AdDialog
     */
    public function getDialog($id)
    {
        if (!$dialog = AdDialog::find()->with('adMessages.user')->andWhere(['id' => $id])->limit(1)->one()) {
            throw new NotFoundException('Dialog not found.');
        }
        return $dialog;
    }

    /**
     * @param $id
     * @return \yii\db\ActiveRecord|Dialog
     */
    public function getMessageDialog($id)
    {
        if (!$dialog = Dialog::find()->with('messages.user')->andWhere(['id' => $id])->limit(1)->one()) {
            throw new NotFoundException('Dialog not found.');
        }
        return $dialog;
    }

    public function save(AdMessage $message)
    {
        if (!$message->save()) {
            throw new \DomainException('Saving error.');
        }
    }

    public function saveDialog(AdDialog $dialog)
    {
        if (!$dialog->save()) {
            throw new \DomainException('Saving error.');
        }
    }

    public function saveMessageDialog(Dialog $dialog)
    {
        if (!$dialog->save()) {
            throw new \DomainException('Saving error.');
        }
    }

    public function saveMessage(Message $message)
    {
        if (!$message->save()) {
            throw new \DomainException('Saving error.');
        }
    }

    /**
     * @param array $condition
     * @return \yii\db\ActiveRecord|AdMessage
     */
    private function getBy(array $condition)
    {
        if (!$message = AdMessage::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Message not found.');
        }

        return $message;
    }

    /**
     * @param int $adId
     * @param int $adType
     * @param int $userId
     * @return AdDialog|null|\yii\db\ActiveRecord
     */
    public function findDialog($adId, $adType, $userId)
    {
        return AdDialog::find()->where(['ad_id' => $adId, 'ad_type' => $adType, 'user_id' => $userId])->one();
    }

    /**
     * @param int $userOne
     * @param int $userTwo
     * @return Dialog|array|null|\yii\db\ActiveRecord
     */
    public function findMessageDialog($userOne, $userTwo)
    {
        return Dialog::find()
            ->where(['user_one' => [$userOne, $userTwo], 'user_two' => [$userOne, $userTwo]])->one();
    }

    /**
     * @param int $userId
     * @return ActiveDataProvider
     */
    public function getDialogsByUserId($userId)
    {
        return new ActiveDataProvider([
            'query' => AdDialog::find()
                ->alias('d')
                ->innerJoin('{{%ad_messages}} m', 'm.`dialog_id`=d.`id`')
                ->where(['or', ['d.user_id' => $userId], ['d.owner_id' => $userId]])
                ->orderBy(['m.is_new' => SORT_DESC,'d.id' => SORT_DESC])
                ->groupBy('d.id'),
            'pagination' => ['pageSize' => 30]
        ]);
    }

    public function getMessageDialogsByUserId($userId)
    {
        return new ActiveDataProvider([
            'query' => Dialog::find()
                ->alias('d')
                ->innerJoin('{{%messages}} m', 'm.`dialog_id`=d.`id`')
                ->where(['or', ['d.user_one' => $userId], ['d.user_two' => $userId]])
                ->orderBy(['m.is_new' => SORT_DESC,'d.id' => SORT_DESC])
                ->groupBy('d.id'),
            'pagination' => ['pageSize' => 30]
        ]);
    }

    public function getMessagesCountByUserId($userId)
    {
        return AdMessage::find()
            ->alias('m')
            ->innerJoin('{{%ad_dialogs}} d', 'd.id=m.dialog_id')
            ->where(['or', ['d.user_id' => $userId], ['d.owner_id' => $userId]])
            ->count()
            +
            Message::find()
                ->alias('m')
                ->innerJoin('{{%dialogs}} d', 'd.id=m.dialog_id')
                ->where(['or', ['d.user_one' => $userId], ['d.user_two' => $userId]])
                ->count();
    }

    public function deleteDialog($id)
    {
        $this->getDialog($id)->delete();
    }
}