<?php

namespace app\helpers;

use app\modules\main\forms\AddPartForm;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use Yii;
use yii\helpers\ArrayHelper;

class AutoHelper
{
    /* Типы кузова */
    public const BODY_STYLES = [
        1 => "Седан",
        2 => "Хэтчбэк",
        3 => "Универсал",
        4 => "Купе",
        5 => "Минивен",
        6 => "Внедорожник",
        7 => "Микроавтобус",
        8 => "Каблучок",
        9 => "Пикап",
    ];

    /* Объём двигателя */
    public const ENGINE_VOLUMES = [
        10 => 1.0,
        13 => 1.3,
        14 => 1.4,
        15 => 1.5,
        16 => 1.6,
        20 => 2.0,
        24 => 2.4,
        25 => 2.5,
        27 => 2.7,
        30 => 3.0,
        35 => 3.5,
    ];

    /* Тип двигателя */
    public const FUEL_TYPES = [
        1 => "Бензин",
        2 => "Турбобензин",
        3 => "Дизель",
        4 => "Турбодизель",
        5 => "Газ/бензин",
        6 => "Гибрид",
    ];

    public const FUEL_TYPES_SHOT = [
        1 => "Б",
        2 => "ТБ",
        3 => "Д",
        4 => "ТД",
        5 => "Г/Б",
        6 => "Г",
    ];

    public const TRANSMISSION_TYPES = [
        1 => "Механика",
        2 => "Автомат",
        3 => "Робот",
        4 => "Вариатор",
    ];

    public const DRIVETRAIN_TYPES = [
        1 => "Передний",
        2 => "Задний",
        3 => "Полный",
    ];

    public const COLORS_ARRAY = [
        1 => "Белый",
        2 => "Чёрный",
        3 => "Красный",
        4 => "Синий",
        5 => "Зелёный",
        6 => "Серебристый",
        7 => "Серый",
    ];

    /**
     * @return array
     */
    public static function fuelTypesList()
    {
        return self::FUEL_TYPES;
    }

    /**
     * @return array
     */
    public static function engineVolumesList()
    {
        return self::ENGINE_VOLUMES;
    }

    /**
     * @return array
     */
    public static function bodyStylesList()
    {
        return self::BODY_STYLES;
    }

    /**
     * @return array
     */
    public static function transmissionList()
    {
        return self::TRANSMISSION_TYPES;
    }

    /**
     * @return array
     */
    public static function drivetrainList()
    {
        return self::DRIVETRAIN_TYPES;
    }

    /**
     * @return array
     */
    public static function colorList()
    {
        return self::COLORS_ARRAY;
    }

    /**
     * @return array
     */
    public static function getYearsArray()
    {
        $arr = [];
        for ($i = date('Y'); $i >= 1950; $i--) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    /**
     * @param integer $style_id
     * @return mixed
     */
    public static function getBodyStyleName($style_id)
    {
        return ArrayHelper::getValue(self::BODY_STYLES, $style_id, "Не указан");
    }

    /**
     * @return array|AutoBrand[]
     */
    public static function getBrands()
    {
        return AutoBrand::find()->all();
    }

    /**
     * @param $id
     * @return false|string
     */
    public static function getBrandNameById($id)
    {
        $name = AutoBrand::find()->select('name')->where(['id' => $id])->scalar();
        return $name ? $name : "";
    }

    /**
     * @param $id
     * @return false|string
     */
    public static function getModelNameById($id)
    {
        $name = AutoModel::find()->select('name')->where(['id' => $id])->scalar();
        return $name ? $name : "";
    }

    /**
     * @return array
     */
    public static function getPartsCategories()
    {
        $cats = require Yii::getAlias('@app/data/spare_parts.php');
        return is_array($cats) ? $cats : [];

    }

    /**
     * @param integer $id
     * @return string
     */
    public static function getTransmissionName($id)
    {
        return ArrayHelper::getValue(self::transmissionList(), $id, "Не указано");
    }

    /**
     * @param integer $id
     * @return string
     */
    public static function getDrivetrainName($id)
    {
        return ArrayHelper::getValue(self::drivetrainList(), $id, "Не указано");
    }

    /**
     * @param integer $id
     * @return string
     */
    public static function getColorName($id)
    {
        return ArrayHelper::getValue(self::colorList(), $id, "Не указано");
    }

    /**
     * @return array
     */
    public static function getOdometersArray()
    {
        return [
            10000 => '10 000',
            20000 => '20 000',
            30000 => '30 000',
            50000 => '50 000',
            100000 => '100 000',
            150000 => '150 000',
            200000 => '200 000',
            300000 => '300 000',
            500000 => '500 000',
        ];
    }

    /**
     * @return array
     */
    public static function getPriceArray()
    {
        return [
            500 => 500,
            1000 => 1000,
            2500 => 2500,
            5000 => 5000,
            10000 => "10 000",
            25000 => "25 000",
            50000 => "50 000",
            100000 => "100 000",
            250000 => "250 000",
            500000 => "500 000",
            1000000 => "1 000 000",
        ];
    }

    /**
     * Типы шин
     * @return array
     */
    public static function getTireTypesArray()
    {
        return [
            1 => 'Легковые',
            //2 => 'Внедорожные',
            //3 => 'Грузовые',
            //4 => 'Мото'
        ];
    }

    /**
     * @return array
     */
    public static function getTireSeasonsArray()
    {
        return [
            1 => 'Летние',
            2 => 'Всесезонные',
            3 => 'Зимние',
        ];
    }

    /**
     * @return array
     */
    public static function getTireRadiusArray()
    {
        return [
            12 => 'R12',
            13 => 'R13',
            14 => 'R14',
            15 => 'R15',
            16 => 'R16',
            17 => 'R17',
            18 => 'R18',
            19 => 'R19',
            20 => 'R20',
            21 => 'R21',
            22 => 'R22',
        ];
    }

    /**
     * @return array
     */
    public static function getTireWidthArray()
    {
        $array = [];
        for ($i = 155; $i <= 285; $i += 10) {
            $array[$i] = $i;
        }

        return $array;
    }

    /**
     * @return array
     */
    public static function getTireAspectRatioArray()
    {
        $array = [];
        for ($i = 40; $i <= 85; $i += 5) {
            $array[$i] = $i;
        }

        return $array;
    }

    /**
     * @return array
     */
    public static function getTireAmountArray()
    {
        return [
            1 => '1 шт',
            2 => '2 шт',
            3 => '3 шт',
            4 => '4 шт',
            5 => '5 шт и более',
        ];
    }

    /**
     * @return array
     */
    public static function getTireConditionArray()
    {
        return [
            6 => '6 баллов новое',
            5 => '5 баллов почти как новое',
            4 => '4 балла в хорошем состоянии',
            3 => '3 балла в удовлетворительном состоянии',
            2 => '2 балла в плохом состоянии',
            1 => '1 балл неисправно, на запчасти',
        ];
    }

    /**
     * @param bool $for
     * @return array
     */
    public static function getWheelAutoArray($for = false)
    {
        return [
            1 => $for ? 'Для легковых авто' : 'Легковые',
            // 2 => $for ? 'Для внедорожн. авто' : 'Внедорожные',
            // 3 => $for ? 'Для грузовых авто' : 'Грузовые',
            // 4 => $for ? 'Для мототехники' : 'Мото',
        ];
    }

    /**
     * @return array
     */
    public static function getWheelTypesArray()
    {
        return [
            1 => 'Литые',
            2 => 'Железные',
            3 => 'Кованные',
        ];
    }

    /**
     * @return array
     */
    public static function getWheelBoltsArray()
    {
        return [
            4 => 4,
            5 => 5,
            6 => 6,
        ];
    }
}