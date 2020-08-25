<?php

namespace app\modules\admin\grid;


use app\board\entities\AutoService;
use yii\bootstrap\Html;
use yii\grid\DataColumn;

class AutoServicePlaceColumn extends DataColumn
{
    public $label = "Место";
    public $format = "raw";
    public $workID;

    protected function renderDataCellContent($model, $key, $index)
    {
        /* @var $model AutoService */
        $this->content = '<div class="button-group">';
        $place = $model->getPlaceByWorkID($this->workID);

        $buttonUp = Html::button(Html::tag("span", "", ['class' => 'fa-arrow-up']), ['class' => 'btn btn-sm btn-default']);
        $buttonDown = Html::button(Html::tag("span", "", ['class' => 'fa-arrow-down']), ['class' => 'btn btn-sm btn-default']);

        $this->content .= $place > 1 ? Html::a($buttonUp, ['move-up', 'id' => $model->id]) : $buttonUp;
        $this->content .= Html::button($place, ['class' => 'btn btn-sm btn-default']);
        $this->content .= Html::a($buttonDown, ['move-down', 'id' => $model->id]);

        return parent::renderDataCellContent($model, $key, $index);
    }
}