<?php

namespace app\modules\main\models\filters;


use app\modules\main\models\AdPart;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class PartsFilter extends ActiveRecord
{
    public $brand;
    public $model;
    public $cat;
    public $sub_cats;

    public function __construct(array $config = [])
    {
        $this->brand = Yii::$app->request->get('brand');
        $this->model = $this->brand ? Yii::$app->request->get('model') : null;
        $this->cat = Yii::$app->request->get('cat');
        $this->sub_cats = Yii::$app->request->get('sub_cats', []);

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand', 'model', 'cat'], 'integer'],
            ['sub_cats', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param ActiveQuery $query
     * @return ActiveDataProvider
     */
    public function search(ActiveQuery $query = null)
    {
        if ($query === null) {
            $query = AdPart::find()->active()->with(['model', 'brand', 'user.city']);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['attributes' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => 30,
            ],
        ]);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'brand_id' => $this->brand,
            'model_id' => $this->model,
            'category_id' => $this->sub_cats ? $this->sub_cats : $this->cat,
        ]);

        return $dataProvider;
    }

    /**
     * @param string $attribute
     * @param string|int $value
     * @return int
     */
    public function getCount($attribute, $value)
    {
        $query = AdPart::find()
            ->active()
            ->andFilterWhere([
                'brand_id' => $this->brand,
                'model_id' => $this->model,
            ]);

        $query->andWhere([$attribute => $value]);

        return AdPart::getDb()->cache(function() use ($query) {
            return $query->count();
        }, Yii::$app->params['cache.filterCountsTime']);
    }
}