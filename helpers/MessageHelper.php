<?php

namespace app\helpers;


use app\board\entities\AdMessage\AdMessage;
use app\board\entities\Message\Message;
use Yii;
use yii\bootstrap\Html;

class MessageHelper
{
    public static function getNewMessagesCount()
    {
        $count = AdMessage::find()
            ->alias('m')
            ->innerJoin('{{%ad_dialogs}} d', 'd.id=m.dialog_id')
            ->where(['or', ['d.user_id' => Yii::$app->user->id], ['d.owner_id' => Yii::$app->user->id]])
            ->andWhere(['not', ['m.user_id' => Yii::$app->user->id]])
            ->andWhere(['m.is_new' => 1])
            ->count() +
            Message::find()
                ->alias('m')
                ->innerJoin('{{%dialogs}} d', 'd.id=m.dialog_id')
                ->where(['or', ['d.user_one' => Yii::$app->user->id], ['d.user_two' => Yii::$app->user->id]])
                ->andWhere(['not', ['m.user_id' => Yii::$app->user->id]])
                ->andWhere(['m.is_new' => 1])
                ->count();

        return !$count ? '' : "(" . Html::tag('span', $count, ['class' => 'count']) . ")";
    }
}