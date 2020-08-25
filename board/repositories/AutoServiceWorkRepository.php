<?php

namespace app\board\repositories;


use app\board\entities\AutoServiceCategory;
use app\board\entities\AutoServiceWork;

class AutoServiceWorkRepository
{
    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord|AutoServiceCategory
     */
    public function getCategory($id)
    {
        if (!$category = AutoServiceCategory::find()->andWhere(['id' => $id])->limit(1)->one()) {
            throw new NotFoundException('Category not found.');
        }

        return $category;
    }

    public function save(AutoServiceWork $autoServiceWork)
    {
        if (!$autoServiceWork->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function saveCategory(AutoServiceCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param array $condition
     * @return \yii\db\ActiveRecord|AutoServiceWork
     */
    private function getBy(array $condition)
    {
        if (!$autoServiceWork = AutoServiceWork::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Вид работ не найден.');
        }

        return $autoServiceWork;
    }
}