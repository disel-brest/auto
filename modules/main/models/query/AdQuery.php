<?php

namespace app\modules\main\models\query;
use app\modules\main\models\Ad;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\main\models\AdCar]].
 *
 * @see \app\modules\main\models\AdCar
 */
class AdQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere([($this->modelClass)::tableName() . '.status' => Ad::STATUS_ACTIVE]);
    }

    /**
     * @return $this
     */
    public function forCabinet()
    {
        return $this->andWhere(['not', ['status' => [Ad::STATUS_DELETED, Ad::STATUS_PREVIEW]]])
            ->andWhere(['user_id' => \Yii::$app->user->id]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\main\models\AdCar[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\main\models\AdCar|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
