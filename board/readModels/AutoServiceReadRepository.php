<?php

namespace app\board\readModels;


use app\board\entities\AutoService;
use app\board\entities\AutoServicesWorksAssignment;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class AutoServiceReadRepository
{
    public function find($id)
    {
        return AutoService::find()->where(['id' => $id])->one();
    }

    public function getAll()
    {
        $query = AutoService::find()
            ->alias('s')
            ->with('city', 'works')
            ->innerJoin(AutoServicesWorksAssignment::tableName() . " a", 'a.service_id=s.id')
            ->orderBy(['a.sort' => SORT_ASC]);
        return $this->getProvider($query);
    }

    public function getByWorkID($workID)
    {
        $query = AutoService::find()
            ->alias('s')
            ->with('city', 'works')
            ->innerJoin(AutoServicesWorksAssignment::tableName() . " a", 'a.service_id=s.id')
            ->where(['a.work_id' => $workID])
            ->orderBy(['a.sort' => SORT_ASC]);
        return $this->getProvider($query);
    }

    public function count()
    {
        return AutoService::find()->count();
    }

    private function getProvider(ActiveQuery $query)
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            /*'pagination' => [
                'pageSizeLimit' => [10, 100],
            ]*/
        ]);
    }

}