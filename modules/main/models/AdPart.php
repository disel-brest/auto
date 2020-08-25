<?php

namespace app\modules\main\models;

use app\board\entities\AdInterface;
use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\query\AdPartQuery;
use app\modules\user\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%ad_part}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $brand_id
 * @property integer $model_id
 * @property integer $fuel_id
 * @property integer $engine_volume
 * @property integer $year
 * @property integer $body_style
 * @property integer $category_id
 * @property string $name
 * @property string $description
 * @property string|array $photo
 * @property integer $price
 * @property integer $status
 * @property integer $views
 * @property integer $active_till
 * @property string $city [varchar(255)]
 * @property string $region [varchar(100)]
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $statusName
 * @property string $autoFullName
 * @property string $fuelName
 * @property string $engineVolume
 * @property int|string $yearNormal
 * @property string $bodyStyle
 * @property string $photoUrl
 * @property string $categoryName
 *
 * @property AutoModel $model
 * @property AutoBrand $brand
 * @property User $user
 */
class AdPart extends Ad implements AdInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_part}}';
    }

    /**
     * @return int
     */
    public static function type()
    {
        return Ad::TYPE_PART;
    }

    public function getFullName()
    {
        return $this->autoFullName . " " . $this->name;
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
            [['user_id', 'brand_id', 'model_id', 'name', 'category_id', 'status'], 'required'],
            [['user_id', 'brand_id', 'model_id', 'fuel_id', 'engine_volume', 'year', 'body_style', 'category_id', 'price'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['photo'], 'string', 'max' => 38],
            [['model_id'], 'exist', 'skipOnError' => false, 'targetClass' => AutoModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => false, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],

            ['fuel_id', 'default', 'value' => 0],
            ['engine_volume', 'default', 'value' => 0],
            ['year', 'default', 'value' => 0],
            ['body_style', 'default', 'value' => 0],
            ['status', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'brand_id' => 'Марка авто',
            'model_id' => 'Модель авто',
            'fuel_id' => 'Тип двигателя',
            'engine_volume' => 'Объём двигателя',
            'year' => 'Год',
            'body_style' => 'Кузов',
            'categoryName' => 'Категория',
            'name' => 'Название',
            'description' => 'Описание',
            'photo' => 'Фото',
        ]);
    }

    public function afterFind()
    {
        try {
            $this->photo = Json::decode($this->photo);
        } catch (InvalidParamException $e) {
            $this->photo = [$this->photo];
        }
        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if (is_array($this->photo)) {
            $this->photo = Json::encode($this->photo);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return string
     */
    public function getAutoFullName()
    {
        return $this->brand->name . " " . $this->model->name;
    }

    /**
     * @param bool $shot
     * @return string
     */
    public function getFuelName($shot = false)
    {
        return ArrayHelper::getValue($shot ? AutoHelper::FUEL_TYPES_SHOT : AutoHelper::FUEL_TYPES, $this->fuel_id, "-");
    }

    /**
     * @return mixed
     */
    public function getEngineVolume()
    {
        return ArrayHelper::getValue(AutoHelper::ENGINE_VOLUMES, $this->engine_volume, "-");
    }

    /**
     * @return int|string
     */
    public function getYearNormal()
    {
        return $this->year ? $this->year : "-";
    }

    /**
     * @return string
     */
    public function getBodyStyle()
    {
        return ArrayHelper::getValue(AutoHelper::BODY_STYLES, $this->body_style, "-");
    }

    /**
     * @param int $n
     * @param string $for
     * @return string
     */
    public function getPhotoUrl($n = 0, $for = "")
    {
        if ($n === null && $this->photo) {
            foreach ($this->photo as $k => $photo) {
                $n = $k;
                break;
            }
        }
        return $this->photo && isset($this->photo[$n]) ? $this->getFilesPath(true) . "/" . PhotoHelper::getNameFor($this->photo[$n], $for) : "/images/no-photo.png";
    }

    public function getCategoryName()
    {
        $categories = AutoHelper::getPartsCategories();
        foreach ($categories as $category) {
            if ($category['id'] == $this->category_id) {
                return $category['title'];
            }

            if (isset($category['sub_categories'])) {
                $check = $this->checkCategoryArray($category['sub_categories']);
                if ($check) {
                    return $check;
                }
            }
        }

        return "не найдена";
    }

    /**
     * @param bool $usd
     * @return string
     */
    public function getPriceNormal($usd = false)
    {
        return !$usd
            ? number_format($this->price, 0, '.', ' ')
            : number_format(Yii::$app->currency->exchange($this->price), 2, ".", " ");
    }

    private function checkCategoryArray($array)
    {
        foreach ($array as $cat) {
            if ($cat['id'] == $this->category_id) {
                return $cat['title'];
            }
        }

        return false;
    }

    /**
     * @return string

    public function getUrl()
    {
        return Url::to(['/main/parts/view', 'id' => $this->id]);
    }*/

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
     * @inheritdoc
     * @return AdPartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdPartQuery(get_called_class());
    }
}
