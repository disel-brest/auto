<?php

namespace app\commands;

use app\modules\main\components\Counter;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;


class CronController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @internal param string $message the message to be echoed.
     */
    public function actionActiveTimeChecker()
    {
        foreach (AdPart::find()->where(['status' => Ad::STATUS_ACTIVE])->each() as $ad) {
            $this->checkTime($ad);
        }

        foreach (AdCar::find()->where(['status' => Ad::STATUS_ACTIVE])->each() as $ad) {
            $this->checkTime($ad);
        }

        foreach (AdTire::find()->where(['status' => Ad::STATUS_ACTIVE])->each() as $ad) {
            $this->checkTime($ad);
        }
    }

    public function actionCounterUpdate()
    {
        (new Counter(\Yii::$app->getCache()))->update();
    }

    public function actionTempCleaner()
    {
        $files = FileHelper::findFiles(Yii::getAlias("@webroot/tmp"));
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) != 'gitignore' && filectime($file) < (time() - 86400)) {
                unlink($file);
            }
        }
    }

    /**
     * @param Ad $ad
     */
    private function checkTime($ad)
    {
        if (time() >= $ad->active_till) {
            $ad->updateAttributes(['status' => Ad::STATUS_INACTIVE]);
        }
    }
}
