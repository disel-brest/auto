<?php

namespace app\modules\main\forms;

use app\helpers\AdHelper;
use app\helpers\AutoHelper;
use app\helpers\GeoHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdWheel;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\City;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property User $_user
 */
class AddWheelForm extends Model
{
    public $auto_type;
    public $is_new;
    public $wheel_type;
    public $auto_brand_id;
    public $firm;
    public $radius;
    public $bolts;
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
    const SCENARIO_UPDATE = "update";

    private $_user = false;
    private $_adWheel = null;

    public function __construct(AdWheel $adWheel = null, array $config = [])
    {
        $this->_adWheel = $adWheel;
        $this->_user = !$adWheel ? Yii::$app->user->identity : $adWheel->user;
        parent::__construct($config);
    }

    public function init()
    {
        /*$this->city = $this->_user->city_id ? $this->_user->city->name : "";
        $this->phone_operator = $this->_user->phone_operator;
        $this->phone = $this->_user->phone;
        $this->call_time_from = $this->_user->callTime['from'];
        $this->call_time_to = $this->_user->callTime['to'];*/
        $this->auto_type = 1;

        if ($this->_adWheel) {
            $this->id = $this->_adWheel->id;
            $this->status = $this->_adWheel->status;
            $this->auto_type = $this->_adWheel->wheel_auto;
            $this->is_new = $this->_adWheel->is_new;
            $this->wheel_type = $this->_adWheel->wheel_type;
            $this->auto_brand_id = $this->_adWheel->auto_brand_id;
            $this->firm = $this->_adWheel->firm;
            $this->radius = $this->_adWheel->radius;
            $this->bolts = $this->_adWheel->bolts;

            $this->amount = $this->_adWheel->amount;
            $this->price = $this->_adWheel->price;
            $this->priceUSD = $this->_adWheel->price_usd;
            $this->bargain = $this->_adWheel->bargain;
            $this->description = $this->_adWheel->description;
            $this->condition = $this->_adWheel->condition;
            //$this->city = GeoHelper::getCityIdByName($this->_adWheel->city ?: ($this->_user->city_id ? $this->_user->city->name : ""));


            $photos = $this->_adWheel->getPhotos();
            foreach ($photos as $photo) {
                $this->saved_photos[] = $this->_adWheel->getFilesPath(true) . "/" . $photo;
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

            ['auto_type', 'required', 'message' => 'Укажите тип'],
            ['auto_type', 'in', 'range' => array_keys(AutoHelper::getWheelAutoArray())],

            ['is_new', 'required', 'message' => 'Новые или нет?'],
            ['is_new', 'boolean'],

            ['wheel_type', 'required', 'message' => 'Укажите тип'],
            ['wheel_type', 'in', 'range' => array_keys(AutoHelper::getWheelTypesArray())],

            //['auto_brand_id', 'required', 'message' => 'Выберите модель авто'],
            ['auto_brand_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['auto_brand_id' => 'id']],

            //['firm', 'required', 'message' => 'Укажите название фирмы'],
            ['firm', 'filter', 'filter' => 'trim'],
            ['firm', 'string'],

            ['radius', 'required', 'message' => 'Выберите радиус'],
            ['radius', 'in', 'range' => array_keys(AutoHelper::getTireRadiusArray())],

            ['bolts', 'required', 'message' => 'Укажите количество болтов'],
            ['bolts', 'in', 'range' => array_keys(AutoHelper::getWheelBoltsArray())],

            ['amount', 'required', 'message' => 'Укажите количество дисков'],
            ['amount', 'in', 'range' => array_keys(AutoHelper::getTireAmountArray())],

            ['photo', 'required', 'on' => self::SCENARIO_DEFAULT],
            ['photo', 'each', 'rule' => ['file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 3, 'tooBig' => 'Максимальный размер фото - 3 МБ']],

            ['price', 'required', 'message' => 'Укажите цену'],
            ['price', 'integer'],
            ['priceUSD', 'integer'],

            ['bargain', 'required', 'message' => 'Укажите возможность обмена'],
            ['bargain', 'boolean'],

            ['description', 'required'],
            ['description', 'string', 'max' => 255, 'min' => 20],

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
        return array_merge((new AdWheel())->attributeLabels(), [
            'auto_type' => 'Вид',
        ]);
    }

    /**
     * @return bool
     */
    public function add()
    {
        if ($this->validate()) {

            $transaction = AdWheel::getDb()->beginTransaction();

            $adWheel = $this->_adWheel ? $this->_adWheel : new AdWheel();

            if ($adWheel->isNewRecord) {
                $adWheel->user_id = $this->_user->id;
                $adWheel->status = $this->isPreview ? Ad::STATUS_PREVIEW : Ad::STATUS_ACTIVE;
            } else if ($adWheel->status = Ad::STATUS_PREVIEW && !$this->isPreview) {
                $adWheel->status = Ad::STATUS_ACTIVE;
            }

            $adWheel->wheel_auto = $this->auto_type;
            $adWheel->is_new = $this->is_new;
            $adWheel->wheel_type = $this->wheel_type;
            $adWheel->auto_brand_id = $this->auto_brand_id;
            $adWheel->firm = $this->firm;
            $adWheel->radius = $this->radius;
            $adWheel->bolts = $this->bolts;
            $adWheel->amount = $this->amount;
            $adWheel->price = $this->price;
            $adWheel->price_usd = $this->priceUSD ?: 0;
            $adWheel->bargain = $this->bargain;
            $adWheel->description = $this->description;
            $adWheel->condition = $this->condition;

            //$city = GeoHelper::getCityById($this->city);
            $city = $this->_user->city;
            $adWheel->city = $city->name;
            $adWheel->region = $city->region;

            if (!AdHelper::savePhotos($this, $adWheel) || !$adWheel->save(false)) {
                $transaction->rollBack();
                Yii::trace($adWheel->getErrors());
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
            $this->id = $adWheel->id;
            return true;
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
            'auto_type',
            'auto_brand_id',
            'wheel_type',
            'is_new',
            'firm',
            'radius',
            'bolts',
            'amount',
            'description',
            'photo',
            'price',
            'priceUSD',
            'bargain',
            'condition',
        ];
        $scenarios[self::SCENARIO_UPDATE] = $scenarios[self::SCENARIO_DEFAULT];
        return $scenarios;
    }
}
