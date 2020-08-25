<?php

namespace app\modules\main\models;

use app\board\entities\AdInterface;
use app\helpers\AutoHelper;
use app\modules\main\models\query\AdQuery;
use app\modules\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%ad_wheel}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $wheel_auto
 * @property integer $is_new
 * @property integer $wheel_type
 * @property integer $auto_brand_id
 * @property string $firm
 * @property integer $radius
 * @property integer $bolts
 * @property integer $amount
 * @property string $photo
 * @property integer $price
 * @property int $price_usd [int(11) unsigned]
 * @property integer $bargain
 * @property string $description
 * @property integer $condition
 * @property integer $status
 * @property string $views
 * @property integer $active_till
 * @property string $city [varchar(255)]
 * @property string $region [varchar(100)]
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $autoTypeName
 * @property string $wheelTypeName
 * @property string $radiusName
 * @property string $amountName
 *
 * @property AutoBrand $autoBrand
 * @property User $user
 */
class AdWheel extends Ad implements AdInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_wheel}}';
    }

    /**
     * @return int
     */
    public static function type()
    {
        return Ad::TYPE_WHEEL;
    }

    public function getFullName()
    {
        return $this->wheelTypeName . " диски " . $this->firm . " " . $this->radiusName;
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
            [['user_id', 'wheel_auto', 'wheel_type', 'firm', 'radius', 'bolts', 'amount', 'condition', 'active_till'], 'required'],
            [['user_id', 'wheel_auto', 'is_new', 'wheel_type', 'auto_brand_id', 'radius', 'bolts', 'amount', 'price', 'bargain', 'condition', 'status', 'views', 'active_till'], 'integer'],
            [['photo', 'description'], 'string'],
            [['firm'], 'string', 'max' => 255],
            [['auto_brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['auto_brand_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels() ,[
            'wheel_auto' => 'Вид',
            'autoTypeName' => 'Вид',
            'is_new' => 'Новые',
            'wheel_type' => 'Тип',
            'wheelTypeName' => 'Тип',
            'auto_brand_id' => 'Марка авто',
            'firm' => 'Фирма',
            'radius' => 'Радиус',
            'bolts' => 'Кол-во болтов',
            'amount' => 'Количество',
            'amountName' => 'Количество',
            'bargain' => 'Торг',
            'condition' => 'Состояние',
        ]);
    }

    /**
     * @return string
     */
    public function getAutoTypeName()
    {
        return ArrayHelper::getValue(AutoHelper::getWheelAutoArray(), $this->wheel_auto);
    }

    /**
     * @return string
     */
    public function getWheelTypeName()
    {
        return ArrayHelper::getValue(AutoHelper::getWheelTypesArray(), $this->wheel_type);
    }

    /**
     * @return string
     */
    public function getRadiusName()
    {
        return ArrayHelper::getValue(AutoHelper::getTireRadiusArray(), $this->radius);
    }

    /**
     * @return string
     */
    public function getAmountName()
    {
        return ArrayHelper::getValue(AutoHelper::getTireAmountArray(), $this->amount);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoBrand()
    {
        return $this->hasOne(AutoBrand::className(), ['id' => 'auto_brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return AdQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdQuery(get_called_class());
    }
}
