<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\AdPart;

/**
 * PartSearch represents the model behind the search form about `app\modules\main\models\AdPart`.
 */
class PartSearch extends AdPart
{
    public $categoryName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'brand_id', 'model_id', 'fuel_id', 'engine_volume', 'year', 'body_style', 'category_id', 'price', 'status', 'views', 'active_till', 'created_at', 'updated_at', 'categoryName'], 'integer'],
            [['name', 'description', 'photo'], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AdPart::find()->with('model', 'brand');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['categoryName'] = [
            'asc' => ['category_id' => SORT_ASC],
            'desc' => ['category_id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'brand_id' => $this->brand_id,
            'model_id' => $this->model_id,
            'fuel_id' => $this->fuel_id,
            'engine_volume' => $this->engine_volume,
            'year' => $this->year,
            'body_style' => $this->body_style,
            'category_id' => $this->categoryName,
            'price' => $this->price,
            'status' => $this->status,
            'views' => $this->views,
            'active_till' => $this->active_till,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
