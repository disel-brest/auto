<?php

namespace app\helpers;


use yii\helpers\ArrayHelper;

class DateHelper
{
    public static function getDaysArray()
    {
        return [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
            6 => 'Суббота',
            7 => 'Воскресенье',
        ];
    }

    public static function getDay($day)
    {
        return ArrayHelper::getValue(self::getDaysArray(), $day, "День");
    }
}