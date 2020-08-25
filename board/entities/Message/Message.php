<?php

namespace app\board\entities\Message;

use app\modules\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%messages}}".
 *
 * @property integer $id
 * @property integer $dialog_id
 * @property integer $user_id
 * @property string $subject
 * @property string $message
 * @property integer $is_new
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Dialog $dialog
 */
class Message extends ActiveRecord
{
    public static function create($dialogId, $userId, $subject, $message)
    {
        $messageEntity = new static();
        $messageEntity->dialog_id = $dialogId;
        $messageEntity->user_id = $userId;
        $messageEntity->subject = $subject;
        $messageEntity->message = $message;
        $messageEntity->is_new = 1;
        return $messageEntity;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dialog_id', 'user_id', 'subject', 'message'], 'required'],
            [['dialog_id', 'user_id', 'is_new'], 'integer'],
            [['message'], 'string'],
            [['subject'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['dialog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dialog::className(), 'targetAttribute' => ['dialog_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dialog_id' => 'Dialog ID',
            'user_id' => 'User ID',
            'subject' => 'Subject',
            'message' => 'Message',
            'is_new' => 'Is New',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialog()
    {
        return $this->hasOne(Dialog::className(), ['id' => 'dialog_id']);
    }
}
