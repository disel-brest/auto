<?php

namespace app\modules\main\models\filters;


use app\components\Currency;
use app\helpers\FormHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class CarsFilter extends Model
{
    public $brand;
    public $model;
    public $bodyStyle;
    public $year_min;
    public $year_max;
    public $engineVolume_min;
    public $engineVolume_max;
    public $price_min;
    public $price_max;
    public $odometer_min;
    public $odometer_max;
    public $drivetrain;
    public $fuel;
    public $transmission;
    public $change;
    public $lawFirm;
    public $currency;
    public $color;
    public $options;
    public $bargain;
    public $city;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'brand',
                'model',
                'bodyStyle',
                'year_min',
                'year_max',
                'engineVolume_min',
                'engineVolume_max',
                'odometer_min',
                'odometer_max',
                'price_min',
                'price_max',
                'color'
            ], 'integer'],
            [['currency', 'city'], 'string'],
            ['currency', 'in', 'range' => array_keys(Currency::getCurrenciesArray())],
            [['change', 'lawFirm', 'bargain'], 'boolean'],
            [['drivetrain', 'fuel', 'transmission', 'options'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return [
            'change' => 'Обмен',
            'lawFirm' => 'Безнал',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $queryValues
     * @param bool $showAll
     * @return ActiveDataProvider
     */
    public function search($queryValues = [], $showAll = false)
    {
        $query = AdCar::find()->with(['model', 'brand', 'user.city']);

        $showAll ? $query->forCabinet() : $query->active();

        $this->load($queryValues);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $showAll ? 0 : 20,
                'pageSizeParam' => false
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $prices = FormHelper::priceFilter($this->price_min, $this->price_max, $this->currency);
        $price_min = $prices['min'];
        $price_max = $prices['max'];

        // Опции
        $options = [];
        if (is_array($this->options)) {
            //$query->distinct()->joinWith('carOptionsAssignments opts');
            foreach ($this->options as $optionId => $value) {
                if ($value) {
                    $options[] = $optionId;
                    //$query->andFilterWhere(['opts.option_id' => $optionId]);
                }
            }
        }
        if (count($options)) {
            $query->distinct()->joinWith(['carOptionsAssignments opts' => function(ActiveQuery $query) use ($options) {
                $query->where(['opts.option_id' => $options]);
            }]);
        }

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
            'body_style' => $this->bodyStyle,
            'drivetrain' => $this->drivetrain,
            'fuel_id' => $this->fuel,
            'transmission' => $this->transmission,
            'change' => $this->change ? $this->change : null,
            'law_firm' => $this->lawFirm ? $this->lawFirm : null,
            'color' => $this->color,
            'bargain' => $this->bargain,
        ]);

        $query->andFilterWhere(['>=', 'price', $price_min])
            ->andFilterWhere(['<=', 'price', $price_max])
            ->andFilterWhere(['>=', 'year', $this->year_min])
            ->andFilterWhere(['<=', 'year', $this->year_max])
            ->andFilterWhere(['>=', 'engine_volume', $this->engineVolume_min])
            ->andFilterWhere(['<=', 'engine_volume', $this->engineVolume_max])
            ->andFilterWhere(['>=', 'odometer', $this->odometer_min])
            ->andFilterWhere(['<=', 'odometer', $this->odometer_max]);

        return $dataProvider;
    }

    /**
     * @param string $attribute
     * @param string|int $value
     * @return int
     */
    public function getCount($attribute, $value)
    {
        $prices = FormHelper::priceFilter($this->price_min, $this->price_max, $this->currency);
        $price_min = $prices['min'];
        $price_max = $prices['max'];

        $query = AdCar::find()
            ->active()
            ->andFilterWhere([
                'brand_id' => $this->brand,
                'model_id' => $this->model,
                'body_style' => $this->bodyStyle,
            ])
            ->andFilterWhere(['>=', 'price', $price_min])
            ->andFilterWhere(['<=', 'price', $price_max])
            ->andFilterWhere(['>=', 'year', $this->year_min])
            ->andFilterWhere(['<=', 'year', $this->year_max])
            ->andFilterWhere(['>=', 'engine_volume', $this->engineVolume_min])
            ->andFilterWhere(['<=', 'engine_volume', $this->engineVolume_max]);

        $query->andWhere([$attribute => $value]);

        return AdCar::getDb()->cache(function() use ($query) {
            return $query->count();
        }, Yii::$app->params['cache.filterCountsTime']);
    }
}