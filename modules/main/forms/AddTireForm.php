<?php

namespace app\modules\main\forms;

use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\helpers\GeoHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdTire;
use app\modules\main\models\City;
use app\modules\main\models\TireBrand;
use app\modules\main\models\TireModel;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property User $_user
 */
class AddTireForm extends Model
{
    public $brand_id;
    public $model_id;
    public $tire_type;
    public $is_new;
    public $season;
    public $radius;
    public $width;
    public $aspect_ratio;
    public $amount;
    public $photo;
    public $price;
    public $priceUSD;
    public $bargain;
    public $description;
    public $condition;

    /*public $city;
    public $phone_operator;
    public $phone;
    public $call_time_from;
    public $call_time_to;*/

    public $saved_photos;
    public $id;
    public $isPreview;
    public $status;

    const SCENARIO_ADMIN_CREATE = "adminCreate";

    private $_user = false;
    private $_adTire = null;

    public function __construct(AdTire $adTire = null, array $config = [])
    {
        $this->_adTire = $adTire;
        $this->_user = !$adTire ? Yii::$app->user->identity : $adTire->user;
        parent::__construct($config);
    }

    public function init()
    {
        /*$this->city = $this->_user->city_id ? $this->_user->city->name : "";
        $this->phone_operator = $this->_user->phone_operator;
        $this->phone = $this->_user->phone;
        $this->call_time_from = $this->_user->callTime['from'];
        $this->call_time_to = $this->_user->callTime['to'];*/

        if ($this->_adTire) {
            $this->id = $this->_adTire->id;
            $this->status = $this->_adTire->status;
            $this->brand_id = $this->_adTire->brand_id;
            $this->model_id = $this->_adTire->model_id;
            $this->tire_type = $this->_adTire->tire_type;
            $this->is_new = $this->_adTire->is_new;
            $this->season = $this->_adTire->season;
            $this->radius = $this->_adTire->radius;
            $this->width = $this->_adTire->width;
            $this->aspect_ratio = $this->_adTire->aspect_ratio;
            $this->amount = $this->_adTire->amount;
            $this->price = $this->_adTire->price;
            $this->priceUSD = $this->_adTire->price_usd;
            $this->bargain = $this->_adTire->bargain;
            $this->description = $this->_adTire->description;
            $this->condition = $this->_adTire->condition;
            //$this->city = GeoHelper::getCityIdByName($this->_adTire->city ?: ($this->_user->city_id ? $this->_user->city->name : ""));

            $photos = $this->_adTire->getPhotos();
            foreach ($photos as $photo) {
                $this->saved_photos[] = $this->_adTire->getFilesPath(true) . "/" . $photo;
                $this->photo[] = 'old/' . $photo;
            }
        }

        parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['isPreview', 'boolean'],

            ['brand_id', 'required', 'message' => 'Выберите производителя'],
            ['brand_id', 'integer', 'min' => 1],
            ['brand_id', 'exist', 'skipOnError' => false, 'targetClass' => TireBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],

            //['model_id', 'required', 'message' => 'Выберите модель шин'],
            ['model_id', 'integer', 'min' => 1],
            ['model_id', 'exist', 'skipOnError' => false, 'targetClass' => TireModel::className(), 'targetAttribute' => ['model_id' => 'id']],

            ['tire_type', 'required', 'message' => 'Укажите тип'],
            ['tire_type', 'integer'],
            ['tire_type', 'in', 'range' => array_keys(AutoHelper::getTireTypesArray())],

            ['is_new', 'required', 'message' => 'Новые или нет?'],
            ['is_new', 'boolean'],

            ['season', 'required', 'message' => 'Укажите тип'],
            ['season', 'integer', 'min' => 1],
            ['season', 'in', 'range' => array_keys(AutoHelper::getTireSeasonsArray())],

            ['radius', 'required', 'message' => 'Выберите радиус'],
            ['radius', 'integer'],
            ['radius', 'in', 'range' => array_keys(AutoHelper::getTireRadiusArray())],

            ['width', 'required', 'message' => 'Укажите размер'],
            ['width', 'integer'],
            ['width', 'in', 'range' => array_keys(AutoHelper::getTireWidthArray())],

            ['aspect_ratio', 'required', 'message' => 'Укажите размер'],
            ['aspect_ratio', 'in', 'range' => array_keys(AutoHelper::getTireAspectRatioArray())],

            ['amount', 'required', 'message' => 'Укажите количество шин'],
            ['amount', 'in', 'range' => array_keys(AutoHelper::getTireAmountArray())],

            ['photo', 'required', 'message' => 'Необходимо загрузить хотя бы одну фотографию'],
            //['photo', 'each', 'rule' => ['file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 3, 'tooBig' => 'Максимальный размер фото - 3 МБ']],
            ['photo', 'each', 'rule' => ['string']],

            ['price', 'required', 'message' => 'Укажите цену'],
            ['price', 'integer'],
            ['priceUSD', 'integer'],

            ['bargain', 'required', 'message' => 'Укажите возможность обмена'],
            ['bargain', 'boolean'],

            ['description', 'required'],
            ['description', 'string', 'min' => 20, 'max' => 255],

            ['condition', 'required', 'message' => 'Укажите состояние'],
            ['condition', 'in', 'range' => array_keys(AutoHelper::getTireConditionArray())],

            /*['city', 'integer'],

            [['phone_operator', 'phone'], 'required', 'message' => 'Укажите Ваш телефон'],
            ['phone_operator', 'in', 'range' => array_keys(User::getPhoneOperatorsArray())],
            ['phone', 'string', 'min' => 5, 'max' => 32],

            ['call_time_from', 'in', 'range' => array_keys(User::getCallTimeArray())],
            ['call_time_to', 'in', 'range' => array_keys(User::getCallTimeArray())],*/
        ];
    }

    public function attributeLabels()
    {
        return (new AdTire())->attributeLabels();
    }

    /**
     * @return bool
     */
    public function add()
    {
        if ($this->validate()) {

            $transaction = AdTire::getDb()->beginTransaction();

            $adTire = $this->_adTire ? $this->_adTire : new AdTire();

            if ($adTire->isNewRecord) {
                $adTire->user_id = $this->_user->id;
                $adTire->status = $this->isPreview ? Ad::STATUS_PREVIEW : Ad::STATUS_ACTIVE;
            } else if ($adTire->status = Ad::STATUS_PREVIEW && !$this->isPreview) {
                $adTire->status = Ad::STATUS_ACTIVE;
            }

            $adTire->brand_id = $this->brand_id;
            $adTire->model_id = $this->model_id;
            $adTire->tire_type = $this->tire_type;
            $adTire->is_new = $this->is_new;
            $adTire->season = $this->season;
            $adTire->radius = $this->radius;
            $adTire->width = $this->width;
            $adTire->aspect_ratio = $this->aspect_ratio;
            $adTire->amount = $this->amount;
            $adTire->price = $this->price;
            $adTire->price_usd = $this->priceUSD ?: 0;
            $adTire->bargain = $this->bargain;
            $adTire->description = $this->description;
            $adTire->condition = $this->condition;

            //$city = GeoHelper::getCityById($this->city);
            $city = $this->_user->city;
            $adTire->city = $city->name;
            $adTire->region = $city->region;

            if (!AdHelper::savePhotos($this, $adTire) || !$adTire->save(false)) {
                $transaction->rollBack();
                return false;
            }

            // Обновление данных профиля если они изменены во время подачи объявления
            /*if (
                $this->scenario != self::SCENARIO_ADMIN_CREATE &&
                ($city->name != $this->_user->city->name ||
                    $this->phone_operator != $this->_user->phone_operator ||
                    $this->phone != $this->_user->phone ||
                    $this->call_time_from != $this->_user->callTime['from'] ||
                    $this->call_time_to != $this->_user->callTime['to'])
            ) {
                $this->_user->city_id = $city->id;
                $this->_user->phone_operator = $this->phone_operator;
                $this->_user->phone = $this->phone;
                $this->_user->setCallTime($this->call_time_from, $this->call_time_to);
                $this->_user->save();
            }*/

            $transaction->commit();
            $this->id = $adTire->id;
            return true;
        } else {
            Yii::trace($this->errors, 'ERRORS');
        }

        return false;
    }

    public function setUser(User $user)
    {
        $this->_user = $user;
    }

    public function beforeValidate()
    {
        //$this->photo = UploadedFile::getInstances($this, 'photo');
        return parent::beforeValidate();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = [
            'brand_id',
            'model_id',
            'tire_type',
            'is_new',
            'season',
            'radius',
            'width',
            'aspect_ratio',
            'amount',
            'description',
            'photo',
            'price',
            'priceUSD',
            'bargain',
            'condition',
        ];
        return $scenarios;
    }
}
