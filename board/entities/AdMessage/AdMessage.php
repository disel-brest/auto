<?php

namespace app\board\entities\AdMessage;

use app\modules\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ad_messages}}".
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
 * @property AdDialog $dialog
 */
class AdMessage extends ActiveRecord
{
    /**
     * @param int $dialogId
     * @param int $userId
     * @param string $subject
     * @param string $message
     * @param int $isNew
     * @return static
     */
    public static function create($dialogId, $userId, $subject, $message, $isNew = 1)
    {
        $adMessage = new static();
        $adMessage->dialog_id = $dialogId;
        $adMessage->user_id = $userId;
        $adMessage->subject = $subject;
        $adMessage->message = $message;
        $adMessage->is_new = $isNew;
        return $adMessage;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_messages}}';
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
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['dialog_id'], 'exist', 'skipOnError' => false, 'targetClass' => AdDialog::className(), 'targetAttribute' => ['dialog_id' => 'id']],
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
            'message' => 'Сообщение',
            'is_new' => 'Не прочитано',
            'created_at' => 'Дата',
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
        return $this->hasOne(AdDialog::className(), ['id' => 'dialog_id']);
    }
}
