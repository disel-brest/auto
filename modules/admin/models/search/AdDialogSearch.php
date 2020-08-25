<?php

namespace app\modules\admin\models\search;

use app\board\entities\AdMessage\AdMessage;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\board\entities\AdMessage\AdDialog;

/**
 * AdDialogSearch represents the model behind the search form of `app\board\entities\AdMessage\AdDialog`.
 */
class AdDialogSearch extends AdDialog
{
    public $category;
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ad_id', 'ad_type', 'owner_id', 'user_id', 'category'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'dd-M-yyyy'],
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
        //echo $this->date_to;exit;
        $query = AdDialog::find()
            ->alias('d')
            ->innerJoin(AdMessage::tableName() . " m", 'd.id=m.dialog_id');

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
            'ad_id' => $this->ad_id,
            'ad_type' => $this->category,
            'owner_id' => $this->owner_id,
            'd.user_id' => $this->user_id,
        ]);

        $query->andFilterWhere([
            'and',
            ['>=', 'm.created_at', strtotime($this->date_from)],
            ['<=', 'm.created_at', strtotime($this->date_to . " 23:59")],
        ]);

        return $dataProvider;
    }
}
