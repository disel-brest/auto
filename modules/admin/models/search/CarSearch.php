<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\AdCar;

/**
 * CarSearch represents the model behind the search form about `app\modules\main\models\AdCar`.
 */
class CarSearch extends AdCar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'brand_id', 'model_id', 'year', 'odometer', 'body_style', 'fuel_id', 'engine_volume', 'transmission', 'drivetrain', 'color', 'price', 'bargain', 'change', 'law_firm', 'status', 'views', 'active_till', 'created_at', 'updated_at'], 'integer'],
            [['photo', 'description'], 'safe'],
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
        $query = AdCar::find()->with('model', 'brand');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

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
            'year' => $this->year,
            'odometer' => $this->odometer,
            'body_style' => $this->body_style,
            'fuel_id' => $this->fuel_id,
            'engine_volume' => $this->engine_volume,
            'transmission' => $this->transmission,
            'drivetrain' => $this->drivetrain,
            'color' => $this->color,
            'price' => $this->price,
            'bargain' => $this->bargain,
            'change' => $this->change,
            'law_firm' => $this->law_firm,
            'status' => $this->status,
            'views' => $this->views,
            'active_till' => $this->active_till,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
