<?php

namespace app\modules\admin\widgets;


use app\modules\main\models\Ad;
use yii\base\Widget;
use yii\bootstrap\Html;

/**
 * Class StatusSwitcherWidget
 * @package app\modules\admin\widgets
 *
 * @property Ad $ad
 */
class StatusSwitcherWidget extends Widget
{
    public $ad;

    public function run()
    {
        $html = "&nbsp;";

        if (in_array($this->ad->status, [Ad::STATUS_WAITING, Ad::STATUS_BANNED, Ad::STATUS_CLOSED, Ad::STATUS_DELETED, Ad::STATUS_INACTIVE])) {
            $html = Html::a('Сделать активным', ['/admin/default/activate' , 'id' => $this->ad->id, 'type' => $this->ad::type()], ['class' => 'btn btn-success btn-flat']);
        }

        if ($this->ad->status == Ad::STATUS_ACTIVE) {
            $html = Html::a('Забанить', ['/admin/default/ban' , 'id' => $this->ad->id, 'type' => $this->ad::type()], ['class' => 'btn btn-danger btn-flat']) . "&nbsp;" .
                    Html::a('Закрыть', ['/admin/default/close' , 'id' => $this->ad->id, 'type' => $this->ad::type()], ['class' => 'btn btn-warning btn-flat']) . "&nbsp;";
        }

        return $html;
    }
}