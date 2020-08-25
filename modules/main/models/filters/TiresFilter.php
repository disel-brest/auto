<?php

namespace app\modules\main\models\filters;


use app\components\Currency;
use app\helpers\FormHelper;
use app\modules\main\models\AdTire;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TiresFilter extends Model
{
    public $brand;
    public $model;
    public $tire_type;
    public $is_new;
    public $radius;
    public $width;
    public $aspect_ratio;
    public $season;
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
                'brand',
                'model',
                'tire_type',
                'radius',
                'width',
                'aspect_ratio',
                'price_min',
                'price_max',
            ], 'integer'],
            [['is_new'], 'each', 'rule' => ['boolean']],
            [['amount', 'season'], 'each', 'rule' => ['integer']],
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
     * Creates data provider instance with search query applied
     * @param array $queryValues
     * @param bool $showAll
     * @return ActiveDataProvider
     */
    public function search($queryValues = [], $showAll = false)
    {
        $query = AdTire::find()->with(['model', 'brand', 'user.city']);

        $showAll ? $query->forCabinet() : $query->active();

        if ($showAll) {
            $this->tire_type = null;
        }

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
            //print_r($this->errors); exit;
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
            'brand_id' => $this->brand,
            'model_id' => $this->model,
            'tire_type' => $this->tire_type,
            'is_new' => $this->is_new,
            'radius' => $this->radius,
            'width' => $this->width,
            'aspect_ratio' => $this->aspect_ratio,
            'season' => $this->season,
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
        $query = AdTire::find()
            ->active()
            ->andFilterWhere([
                'brand_id' => $this->brand,
                'model_id' => $this->model,
                'tire_type' => $this->tire_type,
                'radius' => $this->radius,
                'width' => $this->width,
                'aspect_ratio' => $this->aspect_ratio,
            ]);

        $query->andWhere([$attribute => $value]);

        return AdTire::getDb()->cache(function() use ($query) {
            return $query->count();
        }, Yii::$app->params['cache.filterCountsTime']);
    }
}