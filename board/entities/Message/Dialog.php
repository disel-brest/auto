<?php

namespace app\board\entities\Message;

use app\modules\user\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%dialogs}}".
 *
 * @property integer $id
 * @property integer $user_one
 * @property integer $user_two
 *
 * @property int $messagesCount
 *
 * @property User $userTwo
 * @property User $userOne
 * @property Message[] $messages
 * @property Message $lastMessage
 * @property User $interlocutor
 */
class Dialog extends ActiveRecord
{
    public static function create($userOne, $userTwo)
    {
        $dialog = new static();
        $dialog->user_one = $userOne;
        $dialog->user_two = $userTwo;
        return $dialog;
    }

    public function getLastMessage()
    {
        return $this->hasOne(Message::className(), ['dialog_id' => 'id'])->orderBy(['{{%messages}}.id' => SORT_DESC]);
    }

    public function getMessagesCount()
    {
        return $this->getMessages()->count();
    }

    public function getInterlocutor()
    {
        return Yii::$app->user->id == $this->user_one ? $this->userTwo : $this->userOne;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dialogs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_one', 'user_two'], 'required'],
            [['user_one', 'user_two'], 'integer'],
            [['user_two'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_two' => 'id']],
            [['user_one'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_one' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_one' => 'User One',
            'user_two' => 'User Two',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTwo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_two']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOne()
    {
        return $this->hasOne(User::className(), ['id' => 'user_one']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['dialog_id' => 'id']);
    }
}
