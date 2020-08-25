<?php

namespace app\modules\main\forms;

use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\helpers\GeoHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use app\modules\main\models\CarOptionsAssignment;
use app\modules\main\models\City;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * @property User $_user
 */
class AddCarForm extends Model
{
    public $brand_id;
    public $model_id;
    public $year;
    public $odometer;
    public $body_style;
    public $fuel_id;
    public $engine_volume;
    public $transmission;
    public $drivetrain;
    public $color;
    public $photo;
    public $price;
    public $bargain;
    public $change;
    public $law_firm;
    public $description;
    public $options;

    public $city;
    public $phone_operator;
    public $phone;
    public $call_time_from;
    public $call_time_to;

    public $saved_photos;
    public $id;
    public $isPreview;
    public $status;

    const SCENARIO_ADMIN_CREATE = "adminCreate";

    private $_user = false;
    private $_adCar = null;

    public function __construct(AdCar $adCar = null, array $config = [])
    {
        $this->_adCar = $adCar;
        $this->_user = !$adCar ? Yii::$app->user->identity : $adCar->user;
        parent::__construct($config);
    }

    public function init()
    {
        $this->city = $this->_user->city_id ? $this->_user->city->name : "";
        $this->phone_operator = $this->_user->phone_operator;
        $this->phone = $this->_user->phone;
        $this->call_time_from = $this->_user->callTime['from'];
        $this->call_time_to = $this->_user->callTime['to'];

        if ($this->_adCar) {
            $this->id = $this->_adCar->id;
            $this->status = $this->_adCar->status;
            $this->brand_id = $this->_adCar->brand_id;
            $this->model_id = $this->_adCar->model_id;
            $this->year = $this->_adCar->year;
            $this->odometer = $this->_adCar->odometer;
            $this->body_style = $this->_adCar->body_style;
            $this->fuel_id = $this->_adCar->fuel_id;
            $this->engine_volume = $this->_adCar->engine_volume;
            $this->transmission = $this->_adCar->transmission;
            $this->drivetrain = $this->_adCar->drivetrain;
            $this->color = $this->_adCar->color;
            $this->price = $this->_adCar->price;
            $this->bargain = $this->_adCar->bargain;
            $this->change = $this->_adCar->change;
            $this->law_firm = $this->_adCar->law_firm;
            $this->description = $this->_adCar->description;
            $this->options = $this->_adCar->getOptions()->select('id')->indexBy('id')->all();
            $this->city = GeoHelper::getCityIdByName($this->_adCar->city ?: ($this->_user->city_id ? $this->_user->city->name : ""));

            $photos = $this->_adCar->getPhotos();
            foreach ($photos as $photo) {
                $this->saved_photos[] = $this->_adCar->getFilesPath(true) . "/" . $photo;
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

            ['brand_id', 'required', 'message' => 'Выберите марку авто'],
            ['brand_id', 'integer', 'min' => 1],
            ['brand_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],

            ['model_id', 'required', 'message' => 'Выберите модель авто'],
            ['model_id', 'integer', 'min' => 1],
            ['model_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoModel::className(), 'targetAttribute' => ['model_id' => 'id']],

            ['year', 'required', 'message' => 'Укажите год выпуска'],
            ['year', 'integer', 'min' => 1950, 'max' => date('Y')],

            ['odometer', 'integer'],

            ['body_style', 'required', 'message' => 'Выберите тип кузова'],
            ['body_style', 'integer', 'min' => 1],
            ['body_style', 'in', 'range' => array_keys(AutoHelper::BODY_STYLES)],

            ['fuel_id', 'required', 'message' => 'Выберите тип двигателя'],
            ['fuel_id', 'integer', 'min' => 1],
            ['fuel_id', 'in', 'range' => array_keys(AutoHelper::FUEL_TYPES)],

            ['engine_volume', 'required', 'message' => 'Выберите объём двигателя'],
            ['engine_volume', 'integer', 'min' => 1],
            ['engine_volume', 'in', 'range' => array_keys(AutoHelper::ENGINE_VOLUMES)],

            ['transmission', 'required', 'message' => 'Выберите тип трансмиссии'],
            ['transmission', 'in', 'range' => array_keys(AutoHelper::TRANSMISSION_TYPES)],

            ['drivetrain', 'required', 'message' => 'Выберите привод'],
            ['drivetrain', 'in', 'range' => array_keys(AutoHelper::DRIVETRAIN_TYPES)],

            ['color', 'required', 'message' => 'Укажите цвет'],
            ['color', 'in', 'range' => array_keys(AutoHelper::COLORS_ARRAY)],

            ['photo', 'each', 'rule' => ['file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 3, 'tooBig' => 'Максимальный размер фото - 3 МБ']],

            ['price', 'required'],
            ['price', 'integer'],

            [['bargain', 'change', 'law_firm'], 'required'],
            [['bargain', 'change', 'law_firm'], 'boolean'],

            ['description', 'string', 'max' => 255],

            ['options', 'required'],
            ['options', 'each', 'rule' => ['integer']],

            ['city', 'integer'],

            ['phone_operator', 'in', 'range' => array_keys(User::getPhoneOperatorsArray())],
            ['phone', 'string', 'max' => 32],

            ['call_time_from', 'in', 'range' => array_keys(User::getCallTimeArray())],
            ['call_time_to', 'in', 'range' => array_keys(User::getCallTimeArray())],
        ];
    }

    public function setUser(User $user)
    {
        $this->_user = $user;
    }

    /**
     * @return bool
     */
    public function add()
    {
        if ($this->validate()) {

            $transaction = AdCar::getDb()->beginTransaction();

            $adCar = $this->_adCar ? $this->_adCar : new AdCar();

            if ($adCar->isNewRecord) {
                $adCar->user_id = $this->_user->id;
                $adCar->status = $this->isPreview ? Ad::STATUS_PREVIEW : Ad::STATUS_ACTIVE;
            } else if ($adCar->status = Ad::STATUS_PREVIEW && !$this->isPreview) {
                $adCar->status = Ad::STATUS_ACTIVE;
            }

            $adCar->brand_id = $this->brand_id;
            $adCar->model_id = $this->model_id;
            $adCar->year = $this->year;
            $adCar->odometer = $this->odometer;
            $adCar->body_style = $this->body_style;
            $adCar->fuel_id = $this->fuel_id;
            $adCar->engine_volume = $this->engine_volume;
            $adCar->transmission = $this->transmission;
            $adCar->drivetrain = $this->drivetrain;
            $adCar->color = $this->color;
            $adCar->price = $this->price;
            $adCar->bargain = $this->bargain;
            $adCar->change = $this->change;
            $adCar->law_firm = $this->law_firm;
            $adCar->description = $this->description;

            $city = GeoHelper::getCityById($this->city);
            $adCar->city = $city->name;
            $adCar->region = $city->region;

            if (!AdHelper::savePhotos($this, $adCar) || !$adCar->save(false)) {
                $transaction->rollBack();
                Yii::trace($adCar->getErrors());
                return false;
            }

            // Сохранение опций
            if (is_array($this->options)) {
                if ($this->_adCar) {
                    CarOptionsAssignment::deleteAll(['ad_car_id' => $this->_adCar->id]);
                }

                foreach ($this->options as $optionId => $value) {
                    if ($value) {
                        $carOptionAssignment = new CarOptionsAssignment();
                        $carOptionAssignment->ad_car_id = $adCar->id;
                        $carOptionAssignment->option_id = $this->scenario == self::SCENARIO_ADMIN_CREATE ? $value : $optionId;
                        if (!$carOptionAssignment->save(false)) {
                            $transaction->rollBack();
                            $this->addError("options", "Ошибка сохранения опции на сервере");
                            return false;
                        }
                    }
                }
            }

            // Обновление данных профиля если они изменены во время подачи объявления
            if (
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
            }

            $transaction->commit();
            $this->id = $adCar->id;
            return true;
        }

        return false;
    }

    public function beforeValidate()
    {
        $this->photo = UploadedFile::getInstances($this, 'photo');
        return parent::beforeValidate();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = [
            'brand_id',
            'model_id',
            'fuel_id',
            'engine_volume',
            'year',
            'odometer',
            'options',
            'body_style',
            'color',
            'transmission',
            'drivetrain',
            'description',
            'photo',
            'price',
            'law_firm',
            'bargain',
            'change',
        ];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'brand_id' => 'Марка авто',
            'model_id' => 'Модель авто',
            'fuel_id' => 'Тип двигатеоя',
            'engine_volume' => 'Объём двигателя',
            'year' => 'Год выпуска авто',
            'odometer' => 'Пробег',
            'options' => 'Опции',
            'body_style' => 'Кузов',
            'color' => 'Цвет',
            'transmission' => 'Коробка передач',
            'drivetrain' => 'Привод',
            'description' => 'Описание',
            'photo' => 'Фото',
            'price' => 'Цена в бел. руб.',
            'law_firm' => 'юридическая фирма (безнал)',
            'bargain' => 'Торг',
            'change' => 'Обмен',
        ];
    }
}
