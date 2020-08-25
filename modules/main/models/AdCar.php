<?php

namespace app\modules\main\models;

use app\board\entities\AdInterface;
use app\helpers\AutoHelper;
use app\modules\main\models\query\AdCarQuery;
use app\modules\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%ad_car}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $brand_id
 * @property integer $model_id
 * @property integer $year
 * @property integer $odometer
 * @property integer $body_style
 * @property integer $fuel_id
 * @property integer $engine_volume
 * @property integer $transmission
 * @property integer $drivetrain
 * @property integer $color
 * @property string $photo
 * @property integer $price
 * @property int $price_usd [int(11) unsigned]
 * @property integer $bargain
 * @property integer $change
 * @property integer $law_firm
 * @property string $description
 * @property integer $status
 * @property integer $views
 * @property integer $active_till
 * @property string $city [varchar(255)]
 * @property string $region [varchar(100)]
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $fullName
 * @property string $odometerNormalize
 * @property string $colorName
 * @property string $drivetrainName
 * @property string $url
 *
 * @property AutoModel $model
 * @property AutoBrand $brand
 * @property User $user
 * @property CarOptionsAssignment[] $carOptionsAssignments
 * @property CarOptions[] $options
 */
class AdCar extends Ad implements AdInterface
{
    private $_type = Ad::TYPE_CAR;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_car}}';
    }

    /**
     * @return int
     */
    public static function type()
    {
        return Ad::TYPE_CAR;
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
            [['user_id', 'brand_id', 'model_id', 'price', 'status', 'year'], 'required'],
            [['user_id', 'brand_id', 'model_id', 'year', 'odometer', 'body_style', 'fuel_id', 'engine_volume', 'transmission', 'drivetrain', 'color', 'price', 'bargain', 'change', 'law_firm', 'status'], 'integer'],
            [['photo', 'description'], 'string'],
            [['model_id'], 'exist', 'skipOnError' => false, 'targetClass' => AutoModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => false, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'brand_id' => 'Brand ID',
            'model_id' => 'Model ID',
            'year' => 'Год выпуска',
            'odometer' => 'Пробег',
            'body_style' => 'Кузов',
            'fuel_id' => 'Тип двигателя',
            'engine_volume' => 'Объём двигателя',
            'transmission' => 'Коробка передач',
            'drivetrain' => 'Drivetrain',
            'color' => 'Цвет',
            'photo' => 'Фотографии',
            'price' => 'Цена',
            'bargain' => 'Торг',
            'change' => 'Обмен',
            'law_firm' => 'Безнал',
            'description' => 'Описание',
            'status' => 'Статус',
            'views' => 'Просмотров',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
       return $this->brand->name . " " . $this->model->name . " " . $this->year . " г.в.";
    }

    public function getOdometerNormalize()
    {
        return $this->odometer ? number_format($this->odometer, 0, '.', ' ') . " км" : "-";
    }

    /**
     * @return string
     */
    public function getColorName()
    {
        return ArrayHelper::getValue(AutoHelper::COLORS_ARRAY, $this->color, "-");
    }

    /**
     * @return string
     */
    public function getDrivetrainName()
    {
        return ArrayHelper::getValue(AutoHelper::DRIVETRAIN_TYPES, $this->drivetrain, "-");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(AutoModel::className(), ['id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(AutoBrand::className(), ['id' => 'brand_id']);
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
    public function getCarOptionsAssignments()
    {
        return $this->hasMany(CarOptionsAssignment::className(), ['ad_car_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(CarOptions::className(), ['id' => 'option_id'])->viaTable('{{%car_options_assignment}}', ['ad_car_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AdCarQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdCarQuery(get_called_class());
    }
}
