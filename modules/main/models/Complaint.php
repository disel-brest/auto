<?php

namespace app\modules\main\models;

use app\modules\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%complaint}}".
 *
 * @property integer $id
 * @property integer $ad_type
 * @property integer $ad_id
 * @property integer $user_id
 * @property string $message
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $statusName
 * @property string $typeName
 *
 * @property User $user
 * @property AdCar|AdPart $ad
 */
class Complaint extends ActiveRecord
{
    const STATUS_NOT_VIEWED = 0;
    const STATUS_VIEWED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%complaint}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_type', 'ad_id'], 'required'],
            [['ad_type', 'ad_id', 'user_id', 'status'], 'integer'],
            ['ad_type', 'in', 'range' => array_keys(Ad::getTypesArray())],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['ad_id', 'adExist'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_type' => 'Тип объявления',
            'typeName' => 'Тип объявления',
            'ad_id' => 'ID объявления',
            'user_id' => 'User ID',
            'statusName' => 'Статус',
            'message' => 'Сообщение',
            'created_at' => 'Дата',
            'updated_at' => 'Обновление',
        ];
    }

    public function adExist($attribute, $params, $validator)
    {
        $class = Ad::getAdClassByType($this->ad_type);
        if (!$class::find()->where(['id' => $this->$attribute])->exists()) {
            $this->addError($attribute, "Объявление не найдено");
        }
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return ArrayHelper::getValue(Ad::getTypesArray(), $this->ad_type);
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_NOT_VIEWED => 'Не просмотрено',
            self::STATUS_VIEWED => 'Просмотрено',
        ];
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
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
    public function getAd()
    {
        return $this->hasOne(Ad::getAdClassByType($this->ad_type), ['id' => 'ad_id']);
    }
}
