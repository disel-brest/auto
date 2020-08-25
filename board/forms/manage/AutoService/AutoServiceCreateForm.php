<?php

namespace app\board\forms\manage\AutoService;


use app\board\forms\CompositeForm;
use app\modules\main\models\City;
use app\modules\user\models\User;
use yii\web\UploadedFile;

/**
 * @property PhotosForm $photos
 */
class AutoServiceCreateForm extends CompositeForm
{
    public $name;
    public $subText;
    public $legalName;
    public $city;
    public $street;
    public $UNP;
    public $year;
    public $phones;
    public $phoneOperators;
    public $site;
    public $workScheduleDay;
    public $workScheduleFrom;
    public $workScheduleTill;
    public $about;
    public $info;
    public $background;
    public $coordinates;
    public $works;
    public $photos;

    public function __construct($config = [])
    {
        $this->photos = new PhotosForm();
        $this->phones = [];
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'legalName', 'city', 'year', 'phones', 'coordinates', 'works', 'workScheduleDay', 'info'], 'required'],
            [['subText', 'name', 'legalName', 'city', 'street', 'UNP', 'site', 'about', 'info', 'coordinates'], 'string'],
            ['city', 'exist', 'skipOnError' => false, 'targetClass' => City::className(), 'targetAttribute' => ['city' => 'name']],
            ['year', 'integer', 'min' => 1900, 'max' => date('Y')],
            ['phones', 'each', 'rule' => ['string', 'min' => 5, 'max' => 32, 'tooShort' => "Телефон должен содержать минимум 5 цифр"]],
            ['phones', function ($attribute, $params, $validator) {
                if (!is_array($this->phones)) {
                    $this->addError($attribute, 'Необходимо указать хотя бы один телефон.');
                    return;
                }
                $error = true;
                foreach ($this->phones as $phone) {
                    if ($phone) {
                        $error = false;
                    }
                }
                if ($error) {
                    $this->addError($attribute, "Необходимо указать хотя бы один телефон.");
                }
            }],
            ['phoneOperators', 'each', 'rule' => ['in', 'range' => array_keys(User::getPhoneOperatorsArray())]],
            ['site', 'match', 'pattern' => '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$_iuS'],
            //[['workScheduleFrom', 'workScheduleTill'], 'each', 'rule' => ['match', 'pattern' => '/^[0-9][0-9]:[0-9][0-9]$/uis']],
            ['workScheduleDay', 'each', 'rule' => ['boolean']],
            ['workScheduleDay', function ($attribute, $params, $validator) {
                $error = true;
                foreach ($this->$attribute as $k => $day) {
                    if ($day && $this->workScheduleTill[$k] && $this->workScheduleFrom[$k]) {
                        $error = false;
                    }
                }
                if ($error) {
                    $this->addError($attribute, "Необходимо указать хотя бы один рабочий день.");
                }
            }],
            [['workScheduleFrom', 'workScheduleTill'], 'validateTime'],
            ['background', 'image', 'skipOnEmpty' => true],
            ['coordinates', 'match', 'pattern' => '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/uis'],
            ['works', 'each', 'rule' => ['integer']],
        ];
    }

    public function validateTime($attribute, $params, $validator)
    {
        foreach ($this->$attribute as $time) {
            if ($time) {
                $time = explode(":", $time);
                if ((int)$time[0] < 0 || (int)$time[0] > 24) {
                    $this->addError($attribute, 'Неверный формат времени. Часы могут быть от 0 до 24');
                } else if (!isset($time[1]) || (int)$time[1] < 0 || (int)$time[1] > 60) {
                    $this->addError($attribute, 'Неверный формат времени. Минуты могут быть от 0 до 60');
                }
            }
        }
    }

    protected function internalForms()
    {
        return ['photos'];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->background = UploadedFile::getInstance($this, 'background');
            return true;
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Общее название',
            'subText' => 'Подтекст',
            'legalName' => 'Юридическое название',
            'city' => 'Город',
            'street' => 'Улица',
            'UNP' => 'УНП',
            'year' => 'Год основания',
            'site' => 'Сайт',
            'workSchedule' => 'График работы',
            'about' => 'Описание',
            'info' => 'Дополнительная информация',
            'background' => 'Фон',
            'lat' => 'Lat',
            'lng' => 'Lng',
            //'photos' => 'Фотографии',
        ];
    }
}