<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\AdWheel;

/**
 * WheelSearch represents the model behind the search form about `app\modules\main\models\AdWheel`.
 */
class WheelSearch extends AdWheel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'wheel_auto', 'is_new', 'wheel_type', 'auto_brand_id', 'radius', 'bolts', 'amount', 'price', 'bargain', 'condition', 'status', 'views', 'active_till', 'created_at', 'updated_at'], 'integer'],
            [['firm', 'photo', 'description'], 'safe'],
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
        $query = AdWheel::find();

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
            'wheel_auto' => $this->wheel_auto,
            'is_new' => $this->is_new,
            'wheel_type' => $this->wheel_type,
            'auto_brand_id' => $this->auto_brand_id,
            'radius' => $this->radius,
            'bolts' => $this->bolts,
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

        $query->andFilterWhere(['like', 'firm', $this->firm])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
