<?php

namespace app\board\entities\AdMessage;

use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use app\modules\main\models\AdWheel;
use app\modules\user\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ad_dialogs}}".
 *
 * @property int $id
 * @property int $ad_id
 * @property int $ad_type
 * @property int $owner_id
 * @property int $user_id
 *
 * @property int $messagesCount
 *
 * @property User $user
 * @property AdMessage[] $adMessages
 * @property AdCar|AdPart|AdTire|AdWheel $ad
 * @property AdMessage $lastMessage
 */
class AdDialog extends ActiveRecord
{
    /**
     * @param int $adId
     * @param int $adType
     * @param int $userId
     * @return static
     */
    public static function create($adId, $adType, $ownerId, $userId)
    {
        $dialog = new static();
        $dialog->ad_id = $adId;
        $dialog->ad_type = $adType;
        $dialog->owner_id = $ownerId;
        $dialog->user_id = $userId;
        return $dialog;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        $class = Ad::getAdClassByType($this->ad_type);
        return $this->hasOne($class, ['id' => 'ad_id']);
    }

    public function getLastMessage()
    {
        return $this->hasOne(AdMessage::className(), ['dialog_id' => 'id'])->orderBy(['{{%ad_messages}}.id' => SORT_DESC]);
    }

    public function getMessagesCount()
    {
        return $this->getAdMessages()->count();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_dialogs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_id', 'ad_type', 'user_id'], 'required'],
            [['ad_id', 'ad_type', 'user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Ad ID',
            'ad_type' => 'Ad Type',
            'user_id' => 'User ID',
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
    public function getAdMessages()
    {
        return $this->hasMany(AdMessage::className(), ['dialog_id' => 'id'])->orderBy(['{{%ad_messages}}.id' => SORT_ASC]);
    }
}
