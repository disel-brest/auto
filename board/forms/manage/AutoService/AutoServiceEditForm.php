<?php

namespace app\board\forms\manage\AutoService;


use app\board\entities\AutoService;
use app\modules\main\models\City;
use app\modules\user\models\User;
use yii\base\Model;
use yii\web\UploadedFile;


class AutoServiceEditForm extends Model
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

    private $_autoService;

    public function __construct(AutoService $autoService, $config = [])
    {
        $this->_autoService = $autoService;
        $this->name = $autoService->name;
        $this->subText = $autoService->sub_text;
        $this->legalName = $autoService->legal_name;
        $this->city = $autoService->city->name;
        $this->street = $autoService->street;
        $this->UNP = $autoService->unp;
        $this->year = $autoService->year;
        foreach ($autoService->getPhones() as $phone) {
            $this->phones[] = $phone[0];
            $this->phoneOperators[] = $phone[1];
        }
        $this->site = $autoService->site;
        foreach ($autoService->getWorkSchedule() as $day => $time) {
            $this->workScheduleDay[$day] = 1;
            $this->workScheduleFrom[$day] = $time[0];
            $this->workScheduleTill[$day] = $time[1];
        }
        $this->about = $autoService->about;
        $this->info = $autoService->info;
        //$this->background = $autoService->name;
        $this->coordinates = $autoService->lat . ", " . $autoService->lng;
        $this->works = $autoService->getWorks()->select('id')->asArray()->column();
        //print_r($this->works);exit;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'legalName', 'city', 'street', 'coordinates'], 'required'],
            [['subText', 'name', 'legalName', 'city', 'street', 'UNP', 'site', 'about', 'info', 'coordinates'], 'string'],
            ['city', 'exist', 'skipOnError' => false, 'targetClass' => City::className(), 'targetAttribute' => ['city' => 'name']],
            ['year', 'integer', 'min' => 1900, 'max' => date('Y')],
            ['phones', 'each', 'rule' => ['string', 'min' => 5, 'max' => 32]],
            ['phoneOperators', 'each', 'rule' => ['in', 'range' => array_keys(User::getPhoneOperatorsArray())]],
            ['site', 'match', 'pattern' => '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$_iuS'],
            //[['workScheduleFrom', 'workScheduleTill'], 'each', 'rule' => ['match', 'pattern' => '/^[0-9][0-9]:[0-9][0-9]$/uis']],
            ['workScheduleDay', 'each', 'rule' => ['boolean']],
            [['workScheduleFrom', 'workScheduleTill'], 'validateTime'],
            ['background', 'image', 'skipOnEmpty' => true],
            ['coordinates', 'match', 'pattern' => '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/uis'],
            ['works', 'each', 'rule' => ['integer']]
        ];
    }

    public function validateTime($attribute, $params, $validator)
    {
        foreach ($this->$attribute as $time) {
            if ($time) {
                $time = explode(":", $time);
                if ((int)$time[0] < 0 || (int)$time[0] > 23) {
                    $this->addError($attribute, 'Неверный формат времени. Часы могут быть от 0 до 24');
                } else if (!isset($time[1]) || (int)$time[1] < 0 || (int)$time[1] > 60) {
                    $this->addError($attribute, 'Неверный формат времени. Минуты могут быть от 0 до 60');
                }
            }
        }
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
        ];
    }
}