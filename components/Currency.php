<?php

namespace app\components;


use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Currency extends Component
{
    //Где брать курсы:
    //https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22USDBYN,BYNUSD%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=

    const CURRENCY_USD = "USD";
    const CURRENCY_BYN = "BYN";

    private $currencyList;

    public function init()
    {
        $json = file_get_contents(\Yii::getAlias("@app/data/currency.json"));
        $this->currencyList = Json::decode($json);
    }

    /**
     * @param float $sum
     * @param string $from
     * @param string $to
     * @return float
     */
    public function exchange($sum, $from = self::CURRENCY_BYN, $to = self::CURRENCY_USD)
    {
        return round($sum * $this->getExchangeValue($from . $to), 2);
    }

    /**
     * Возвращает курс валюты по отношению к доллару
     * @param string $exchange_id
     * @return float
     */
    public function getExchangeValue($exchange_id = self::CURRENCY_BYN . self::CURRENCY_USD)
    {
        return (float) ArrayHelper::getValue($this->currencyList, $exchange_id, 0);
    }

    public static function getCurrenciesArray()
    {
        return [
            self::CURRENCY_BYN => "BYN",
            self::CURRENCY_USD => "$",
        ];
    }
}