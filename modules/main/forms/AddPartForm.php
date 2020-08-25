<?php

namespace app\modules\main\forms;

use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\helpers\GeoHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdPart;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use app\modules\main\models\City;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * @property User $_user
 */
class AddPartForm extends Model
{
    public $brand_id;
    public $model_id;
    public $fuel_id;
    public $engine_volume;
    public $year;
    public $body_style;

    public $category_id;
    public $name;
    public $description;
    public $photo;
    public $price;

    /*public $city;
    public $phone_operator;
    public $phone;
    public $call_time_from;
    public $call_time_to;*/

    private $_user = false;

    const SCENARIO_ADMIN_CREATE = "adminCreate";

    public function __construct(array $config = [])
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        $this->_user = Yii::$app->user->identity;

        parent::__construct($config);
    }

    public function init()
    {
        /*$this->city = $this->_user->city_id;
        $this->phone_operator = $this->_user->phone_operator;
        $this->phone = $this->_user->phone;
        $this->call_time_from = $this->_user->callTime['from'];
        $this->call_time_to = $this->_user->callTime['to'];*/
        $this->photo = [];
        parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['brand_id', 'required', 'message' => 'Выберите марку авто'],
            ['brand_id', 'integer', 'min' => 1],
            ['brand_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],

            ['model_id', 'required', 'message' => 'Выберите модель авто'],
            ['model_id', 'integer', 'min' => 1],
            ['model_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoModel::className(), 'targetAttribute' => ['model_id' => 'id']],

            ['fuel_id', 'required', 'message' => 'Выберите тип двигателя'],
            ['fuel_id', 'integer', 'min' => 1],
            ['fuel_id', 'in', 'range' => array_keys(AutoHelper::FUEL_TYPES)],

            ['engine_volume', 'required', 'message' => 'Выберите объём двигателя'],
            ['engine_volume', 'integer', 'min' => 1],
            ['engine_volume', 'in', 'range' => array_keys(AutoHelper::ENGINE_VOLUMES)],

            ['year', 'required', 'message' => 'Укажите год выпуска'],
            ['year', 'integer', 'min' => 1950, 'max' => date('Y')],

            ['body_style', 'required', 'message' => 'Выберите тип кузова'],
            ['body_style', 'integer', 'min' => 1],
            ['body_style', 'in', 'range' => array_keys(AutoHelper::BODY_STYLES)],

            ['category_id', 'required'],
            ['category_id', 'each', 'rule' => ['integer', 'min' => 1]],

            ['name', 'required'],
            ['name', 'each', 'rule' => ['string', 'max' => 100]],

            ['description', 'each', 'rule' => ['string', 'max' => 255]],

            ['photo', 'each', 'rule' => [
                'each', 'rule' => [
                    'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Максимальный размер фото - 10 МБ']
                ],
            ],

            //['price', 'required'],
            ['price', 'each', 'rule' => ['integer']],

            /*['city', 'string', 'max' => 255],

            [['phone_operator', 'phone'], 'required', 'message' => 'Укажите Ваш телефон'],
            ['phone_operator', 'in', 'range' => array_keys(User::getPhoneOperatorsArray())],
            ['phone', 'string', 'min' => 5, 'max' => 32],

            ['call_time_from', 'in', 'range' => array_keys(User::getCallTimeArray())],
            ['call_time_to', 'in', 'range' => array_keys(User::getCallTimeArray())],*/
        ];
    }

    public function add()
    {
        if ($this->validate()) {
            if (!is_array($this->name)) {
                return false;
            }

            //$city = GeoHelper::getCityById($this->city);
            $transaction = AdPart::getDb()->beginTransaction();

            foreach ($this->name as $k => $name) {
                $adPart = new AdPart();
                $adPart->user_id = $this->_user->id;
                $adPart->brand_id = $this->brand_id;
                $adPart->model_id = $this->model_id;
                $adPart->fuel_id = $this->fuel_id;
                $adPart->engine_volume = $this->engine_volume;
                $adPart->year = $this->year;
                $adPart->body_style = $this->body_style;
                $adPart->category_id = $this->category_id[$k];
                $adPart->name = $name;
                $adPart->description = $this->description[$k];
                $adPart->price = $this->price[$k] ?: 0;
                $adPart->status = AdPart::STATUS_ACTIVE;
                $adPart->city = $this->_user->city_id ? $this->_user->city->name : '';
                $adPart->region = $this->_user->city_id ? $this->_user->city->region : '';

                $photoArr = [];
                if (isset($this->photo[$k]) && is_array($this->photo[$k])) {
                    foreach ($this->photo[$k] as $n => $photo) {
                        if ($photo instanceof UploadedFile) {
                            $photoName = md5(time() . "_" . $k . "_" . $n) . "." . $photo->extension;
                            if (!$photo->saveAs(Yii::getAlias("@webroot/images/users/" . $adPart->user_id . "/" . $photoName))) {
                                $transaction->rollBack();
                                return false;
                            }

                            //Сжатие фото и создание превьшек
                            $photoArr[] = PhotoHelper::createAdImages(Yii::getAlias("@webroot/images/users/" . $adPart->user_id . "/" . $photoName), Ad::TYPE_PART);
                        }
                    }
                }
                $adPart->photo = $photoArr;

                if (!$adPart->save(false)) {
                    $transaction->rollBack();
                    return false;
                }
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
                if ($city->name != $this->_user->city->name) {
                    /*if (($city = City::findOne(['name' => $this->city])) === null) {
                        $this->addError('city', 'Такого города нет в базе');
                        $transaction->rollBack();
                        return false;
                    }*//*

                    $this->_user->city_id = $city->id;
                }

                $this->_user->phone_operator = $this->phone_operator;
                $this->_user->phone = $this->phone;
                $this->_user->setCallTime($this->call_time_from, $this->call_time_to);
                $this->_user->save();
            }*/

            $transaction->commit();
            return true;
        }

        return false;
    }

    public function setUser(User $user)
    {
        $this->_user = $user;
    }

    public function attributeLabels()
    {
        return [
            'brand_id' => 'Марка авто',
            'model_id' => 'Модель авто',
            'fuel_id' => 'Тип двигатеоя',
            'engine_volume' => 'Объём двигателя',
            'year' => 'Год выпуска авто',
            'body_style' => 'Кузов',
            'category_id' => 'Категория',
            'name' => 'Название',
            'description' => 'Описание',
            'photo' => 'Фото',
            'price' => 'Цена в бел. руб.',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['brand_id', 'model_id', 'fuel_id', 'engine_volume', 'year', 'body_style', 'category_id', 'name', 'description', 'photo', 'price'];
        return $scenarios;
    }

    public function beforeValidate()
    {
        if (isset($_FILES[$this->formName()]['name']['photo']) && is_array($_FILES[$this->formName()]['name']['photo'])) {
            foreach ($_FILES[$this->formName()]['name']['photo'] as $k => $photos) {
                foreach ($photos as $n => $photo) {
                    $this->photo[$k][$n] = UploadedFile::getInstanceByName($this->formName() . '[photo]['.$k.']['.$n.']');
                }
            }
        }
        return parent::beforeValidate();
    }
}
