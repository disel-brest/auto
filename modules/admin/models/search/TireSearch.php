<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\AdTire;

/**
 * TireSearch represents the model behind the search form about `app\modules\main\models\AdTire`.
 */
class TireSearch extends AdTire
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'brand_id', 'model_id', 'tire_type', 'is_new', 'season', 'radius', 'width', 'aspect_ratio', 'amount', 'price', 'bargain', 'condition', 'status', 'views', 'active_till', 'created_at', 'updated_at'], 'integer'],
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
        $query = AdTire::find()->with('model', 'brand');

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
            'tire_type' => $this->tire_type,
            'is_new' => $this->is_new,
            'season' => $this->season,
            'radius' => $this->radius,
            'width' => $this->width,
            'aspect_ratio' => $this->aspect_ratio,
            'amount' => $this->amount,
            'price' => $this->price,
            'bargain' => $this->bargain,
            'condition' => $this->condition,
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
