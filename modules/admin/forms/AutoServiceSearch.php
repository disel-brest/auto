<?php

namespace app\modules\admin\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\board\entities\AutoService;

/**
 * AutoServiceSearch represents the model behind the search form of `app\board\entities\AutoService`.
 */
class AutoServiceSearch extends AutoService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'year', 'created_at', 'updated_at', 'status', 'views'], 'integer'],
            [['name', 'sub_text', 'legal_name', 'street', 'unp', 'phones', 'site', 'work_schedule', 'about', 'info', 'background', 'photos'], 'safe'],
            [['lat', 'lng'], 'number'],
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
        $query = AutoService::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'city_id' => $this->city_id,
            'year' => $this->year,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'views' => $this->views,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sub_text', $this->sub_text])
            ->andFilterWhere(['like', 'legal_name', $this->legal_name])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'unp', $this->unp])
            ->andFilterWhere(['like', 'phones', $this->phones])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'work_schedule', $this->work_schedule])
            ->andFilterWhere(['like', 'about', $this->about])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'background', $this->background])
            ->andFilterWhere(['like', 'photos', $this->photos]);

        return $dataProvider;
    }
}
