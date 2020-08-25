<?php

namespace app\modules\main\widgets;


use app\board\entities\AutoServiceCategory;
use yii\base\Widget;

class AutoServicesCategoriesListWidget extends Widget
{
    public $work_id;

    public function run()
    {
        $categories = AutoServiceCategory::find()->with('autoServiceWorks')->all();
        return $this->render('categories-list', [
            'categories' => $categories,
            'work_id' => $this->work_id,
        ]);
    }
}