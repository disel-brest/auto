<?php

namespace app\board\helpers;


use app\board\entities\AutoService;
use app\board\entities\AutoServiceCategory;
use app\board\entities\AutoServicesWorksAssignment;
use app\board\entities\AutoServiceWork;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class AutoServiceHelper
{
    const CACHE_KEY_CATEGORY = "aServByCat_";
    const CACHE_KEY_WORK = "aServByWork_";
    const CACHE_DURATION = 3600;

    public static function generateWorkScheduleArray(array $days, array $from, array $till)
    {
        $result = [];
        foreach ($days as $k => $day) {
            if ($day) {
                $result[$k] = [$from[$k], $till[$k]];
            }
        }

        return $result;
    }

    public static function filesPath($url = true)
    {
        return \Yii::getAlias(($url ? "@web" : "@webroot") . "/images/auto-services");
    }

    public static function getCountByCategoryID($categoryID)
    {
        return \Yii::$app->cache->getOrSet(self::CACHE_KEY_CATEGORY . $categoryID, function () use ($categoryID) {
            return AutoServicesWorksAssignment::find()
                ->alias('a')
                ->innerJoin(AutoServiceWork::tableName() . " w", 'w.id=a.work_id')
                ->where(['w.category_id' => $categoryID])
                ->groupBy('a.service_id')
                ->count();
        }, self::CACHE_DURATION);
    }

    public static function getCountByWorkID($workID)
    {
        return \Yii::$app->cache->getOrSet(self::CACHE_KEY_WORK . $workID, function () use ($workID) {
            return AutoServicesWorksAssignment::find()->where(['work_id' => $workID])->count();
        }, self::CACHE_DURATION);
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $status, "Не установлен");
    }

    public static function getStatusesArray()
    {
        return [
            AutoService::STATUS_NOT_ACTIVE => 'Не активен',
            AutoService::STATUS_ACTIVE => 'Активен'
        ];
    }

    public static function positionButtons(AutoService $autoService)
    {
        $result = "<ul>";
        foreach ($autoService->autoServicesWorksAssignments as $assignment) {
            $result .= "<li>" . $assignment->work->category->name . " - " . $assignment->work->name . " : ";
            $result .= Html::a(Html::tag("span", "", ['class' => 'fa fa-arrow-up']), ['move', 'id' => $autoService->id, 'work_id' => $assignment->work_id, 'to' => 'up'], ['class' => 'btn btn-sm btn-default']);
            $result .= Html::button($assignment->sort, ['class' => 'btn btn-sm btn-default']);
            $result .= Html::a(Html::tag("span", "", ['class' => 'fa fa-arrow-down']), ['move', 'id' => $autoService->id, 'work_id' => $assignment->work_id, 'to' => 'down'], ['class' => 'btn btn-sm btn-default']) . "</li>";
        }
        $result .= "</ul>";

        return $result;
    }
}