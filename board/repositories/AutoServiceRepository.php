<?php

namespace app\board\repositories;


use app\board\entities\AutoService;

class AutoServiceRepository
{
    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }

    public function save(AutoService $autoService)
    {
        if (!$autoService->save()) {
            throw new \DomainException('Saving error.');
        }
    }

    /**
     * @param array $condition
     * @return \yii\db\ActiveRecord|AutoService
     */
    private function getBy(array $condition)
    {
        if (!$autoService = AutoService::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('AutoService not found.');
        }

        return $autoService;
    }
}