<?php

namespace app\modules\main\models;

use app\board\entities\AdInterface;
use app\helpers\AutoHelper;
use app\modules\main\models\query\AdQuery;
use app\modules\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%ad_tire}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $brand_id
 * @property integer $model_id
 * @property integer $tire_type
 * @property integer $is_new
 * @property integer $season
 * @property integer $radius
 * @property integer $width
 * @property integer $aspect_ratio
 * @property integer $amount
 * @property string $photo
 * @property integer $price
 * @property int $price_usd [int(11) unsigned]
 * @property integer $bargain
 * @property string $description
 * @property integer $condition
 * @property integer $status
 * @property integer $views
 * @property integer $active_till
 * @property string $city [varchar(255)]
 * @property string $region [varchar(100)]
 *
 * @property string $tireTypeName
 * @property string $size
 * @property string $radiusName
 * @property string $amountName
 * @property string $isNewName
 * @property string $seasonName
 *
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property TireModel $model
 * @property TireBrand $brand
 * @property User $user
 */
class AdTire extends Ad implements AdInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_tire}}';
    }

    /**
     * @return int
     */
    public static function type()
    {
        return Ad::TYPE_TIRE;
    }

    public function getFullName()
    {
        return $this->tireTypeName . " шины " . $this->brand->name . " " . $this->size . " " . $this->radiusName;
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
            [['user_id', 'brand_id', 'model_id', 'tire_type', 'season', 'radius', 'width', 'aspect_ratio', 'amount', 'condition'], 'required'],
            [['user_id', 'brand_id', 'model_id', 'tire_type', 'is_new', 'season', 'radius', 'width', 'aspect_ratio', 'amount', 'price', 'bargain', 'condition', 'status'], 'integer'],
            [['photo', 'description'], 'string'],
            [['model_id'], 'exist', 'skipOnError' => false, 'targetClass' => TireModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => false, 'targetClass' => TireBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'brand_id' => 'Марка',
            'model_id' => 'Модель',
            'tire_type' => 'Тип',
            'tireTypeName' => 'Тип',
            'is_new' => 'Новые',
            'season' => 'Сезон',
            'seasonName' => 'Сезон',
            'radius' => 'Радиус',
            'radiusName' => 'Радиус',
            'width' => 'Ширина',
            'aspect_ratio' => 'Высота',
            'amount' => 'Количество',
            'amountName' => 'Количество',
            'bargain' => 'Обмен',
            'condition' => 'Состояние',
        ]);
    }

    /**
     * @return string
     */
    public function getTireTypeName()
    {
        return ArrayHelper::getValue(AutoHelper::getTireTypesArray(), $this->tire_type);
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->width . "/" . $this->aspect_ratio;
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
     * @return string
     */
    public function getIsNewName()
    {
        return $this->is_new ? "новые" : "б/у";
    }

    /**
     * @return string
     */
    public function getSeasonName()
    {
        return ArrayHelper::getValue(AutoHelper::getTireSeasonsArray(), $this->season);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(TireModel::className(), ['id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(TireBrand::className(), ['id' => 'brand_id']);
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
