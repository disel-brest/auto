<?php

namespace app\board\readModels\query;


use app\board\entities\AutoService;
use app\board\entities\AutoServicesWorksAssignment;
use yii\db\ActiveQuery;


class AutoServiceQuery extends ActiveQuery
{
    public function byCoordinates($swLat, $swLng, $neLat, $neLng)
    {
        return $this->andWhere(['>', 'lat', $swLat])
            ->andWhere(['<', 'lat', $neLat])
            ->andWhere(['>', 'lng', $swLng])
            ->andWhere(['<', 'lng', $neLng]);
    }

    public function byWorkID($workID)
    {
        return $this->alias('s')
            ->innerJoin(AutoServicesWorksAssignment::tableName() . " a", 'a.service_id=s.id')
            ->andwhere(['a.work_id' => $workID]);
    }

    public function active()
    {
        return $this->andWhere(['status' => AutoService::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return AutoService[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AutoService|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}