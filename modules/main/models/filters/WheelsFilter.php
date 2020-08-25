<?php

namespace app\modules\main\models\filters;


use app\components\Currency;
use app\helpers\FormHelper;
use app\modules\main\models\AdWheel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class WheelsFilter extends Model
{
    public $auto_type;
    public $auto_brand;
    public $wheel_type;
    public $is_new;
    public $radius;
    public $bolts;
    public $amount;
    public $city;
    public $price_min;
    public $price_max;
    public $currency;
    public $bargain;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'auto_type',
                'auto_brand',
                'wheel_type',
                'radius',
                'bolts',
                'price_min',
                'price_max',
            ], 'integer'],
            [['is_new', 'amount'], 'each', 'rule' => ['integer']],
            ['city', 'string'],
            ['bargain', 'boolean'],
            ['currency', 'in', 'range' => array_keys(Currency::getCurrenciesArray())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param array $queryValues
     * @param bool $showAll
     * @return ActiveDataProvider
     */
    public function search($queryValues = [], $showAll = false)
    {
        $query = AdWheel::find()->with(['autoBrand', 'user.city']);

        $showAll ? $query->forCabinet() : $query->active();

        $this->load($queryValues);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $showAll ? 0 : 20,
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $prices = FormHelper::priceFilter($this->price_min, $this->price_max, $this->currency);
        $price_min = $prices['min'];
        $price_max = $prices['max'];

        // Город
        if ($this->city) {
            $query->joinWith('user.city uc');
            $query->andFilterWhere([
                'like', 'uc.name', $this->city
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'wheel_auto' => $this->auto_type,
            'auto_brand_id' => $this->auto_brand,
            'wheel_type' => $this->wheel_type,
            'is_new' => $this->is_new,
            'radius' => $this->radius,
            'bolts' => $this->bolts,
            'amount' => $this->amount,
            'bargain' => $this->bargain,
        ])
            ->andFilterWhere(['>=', 'price', $price_min])
            ->andFilterWhere(['<=', 'price', $price_max]);

        return $dataProvider;
    }

    /**
     * @param string $attribute
     * @param string|int $value
     * @return int
     */
    public function getCount($attribute, $value)
    {
        $query = AdWheel::find()
            ->active()
            ->andFilterWhere([
                'wheel_auto' => $this->auto_type,
                'auto_brand_id' => $this->auto_brand,
                'wheel_type' => $this->wheel_type,
                'radius' => $this->radius,
                'bolts' => $this->bolts,
            ]);

        $query->andWhere([$attribute => $value]);

        return AdWheel::getDb()->cache(function() use ($query) {
            return $query->count();
        }, Yii::$app->params['cache.filterCountsTime']);
    }
}