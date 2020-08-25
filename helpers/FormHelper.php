<?php

namespace app\helpers;


use app\components\Currency;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;

class FormHelper
{
    /**
     * @param ActiveForm $form
     * @param Model $model
     * @param string $attribute
     * @param array $array
     * @return string
     */
    public static function radioList($form, $model, $attribute, $array)
    {
        return $form->field($model, $attribute)->radioList($array, [
            'item' => function($index, $label, $name, $checked, $value) {
                $return = '<div class="middle-btn' . ($checked ? ' active' : '') . '">';
                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>';
                $return .= '<span>' . $label . '</span>';
                $return .= '</div>';
                return $return;
            }
        ])->label(false);
    }

    public static function priceFilter($priceMin, $priceMax, $currency)
    {
        // Преобразуем цену в бел. рубли если юзер указал в долларах в фильтре
        $price_min = $priceMin;
        $price_max = $priceMax;
        if ($currency == Currency::CURRENCY_USD) {
            $price_min = $priceMin ? Yii::$app->currency->exchange($priceMin, Currency::CURRENCY_USD, Currency::CURRENCY_BYN) : null;
            $price_max = $priceMax ? Yii::$app->currency->exchange($priceMax, Currency::CURRENCY_USD, Currency::CURRENCY_BYN) : null;
        }

        return ['min' => $price_min, 'max' => $price_max];
    }
}