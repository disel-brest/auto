<?php

namespace app\helpers;

class PluralForm
{
    /** Склонение существительных с числительными
     * @param int $n число
     * @param string $form1 Единственная форма: 1 секунда
     * @param string $form2 Двойственная форма: 2 секунды
     * @param string $form5 Множественная форма: 5 секунд
     * @return string Правильная форма
     */
    public static function get($n, $form1, $form2, $form5) {
        $str = $n%10 == 1 && $n % 100 != 11 ? $form1 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $form2 : $form5);
        return $n . ' ' . $str;
    }
}